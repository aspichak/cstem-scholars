FROM php:7.4.4-apache

# Run apt update and install some dependancies needed for docker-php-ext
RUN apt update && apt install -y apt-utils sendmail mariadb-client unzip zip libsqlite3-dev libsqlite3-0

# install php extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mysqli

#copy over the website
COPY src/ /var/www/html/
COPY src/prod/config.ini /var/www/html/
COPY src/prod/config.php /var/www/html/

#install and run composer
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN /usr/bin/composer install -o --no-dev
RUN /usr/bin/composer update -o --no-dev

EXPOSE 80/tcp
EXPOSE 443/tcp