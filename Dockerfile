FROM php:7.4.4-apache

# Run apt update and install some dependancies needed for docker-php-ext
RUN apt update && apt install -y apt-utils sendmail mariadb-client unzip zip libsqlite3-dev libsqlite3-0

# install php extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mysqli

COPY src/ /var/www/html/
COPY src/prod/config.ini /var/www/html/
COPY src/prod/config.php /var/www/html/
EXPOSE 80/tcp
