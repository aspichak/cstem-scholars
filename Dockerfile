FROM php:7.4.4-apache

#--------------------------------------------------------------------------------------------
# system config and updates
# update our certificates for cas, just incase
RUN update-ca-certificates

#--------------------------------------------------------------------------------------------
# php config
# Run apt update and install some dependancies needed for docker-php-ext
RUN apt update && apt install -y apt-utils sendmail mariadb-client unzip zip libsqlite3-dev libsqlite3-0

# install php extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mysqli opcache

#--------------------------------------------------------------------------------------------
# apache server config
# Change Apache DocumentRoot to serve from /src/public
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf
# enable http headers module
RUN a2enmod headers
# disallow content sniffing, dogs are banned :P
RUN sed -ri -e 's!#Header set X-Content-Type-Options: "nosniff"!Header set X-Content-Type-Options: "nosniff"!g' /etc/apache2/conf-enabled/security.conf
# ban embedding this page in another
RUN sed -ri -e 's!#Header set X-Frame-Options: "sameorigin"!Header set X-Frame-Options: "deny"!g' /etc/apache2/conf-enabled/security.conf
# enables some level of builtin cross side scripting protection
RUN sed -ri '$iHeader set X-XSS-Protection "1; mode=block"\n' /etc/apache2/conf-enabled/security.conf
# gets rid of referrer head, for the extra paranoid
RUN sed -ri '$iHeader set Referrer-Policy "no-referrer"\n' /etc/apache2/conf-enabled/security.conf
# changes site cookies to be HttpOnly, only accessable from this site and only sent over HTTPS
RUN sed -i '$iHeader edit Set-Cookie ^(.*)$ $1;HttpOnly;Secure;SameSite=Strict\n' /etc/apache2/conf-enabled/security.conf
# force https connections only
RUN sed -i '$iHeader set Strict-Transport-Security "max-age=31536000; includeSubDomains"\n' /etc/apache2/conf-enabled/security.conf

#--------------------------------------------------------------------------------------------
# composer block
# install and run composer - setup to cache for docker
COPY src/composer.json /var/www/html/
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN /usr/bin/composer install -o --no-dev
RUN /usr/bin/composer update -o --no-dev

#--------------------------------------------------------------------------------------------
# website specific block
# copy over the website
COPY src/ /var/www/html/
COPY docker/config.php /var/www/html/

# copy over our php.ini
# we do this last so composer runs with default settings and the opcache preloaded
# gets to run after our libraries are downloaded and isntalled
COPY docker/php.ini /usr/local/etc/php/

EXPOSE 80/tcp
EXPOSE 443/tcp
