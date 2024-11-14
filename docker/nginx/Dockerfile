FROM php:8.3.3-fpm

RUN apt-get update -y && apt-get install -y nginx \
            libzip-dev \
            unzip \
            && docker-php-ext-install zip pdo_mysql


# Install mysqli, zip, pdo, pdo_mysql PHP extension for MySQL support
RUN docker-php-ext-install pdo pdo mysqli
# Copy the application files to the container

COPY docker/nginx/default.conf /etc/nginx/sites-enabled/default
COPY docker/nginx/entrypoint.sh /etc/entrypoint.sh

RUN chmod +x /etc/entrypoint.sh

## Set the working directory
WORKDIR /var/www/html

EXPOSE 80

ENTRYPOINT ["/etc/entrypoint.sh"]
