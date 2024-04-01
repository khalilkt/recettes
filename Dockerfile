FROM php:7.2-apache

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT /var/www/src
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


ENV COMPOSER_ALLOW_SUPERUSER 1



# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN echo "memory_limit = -1\n" >> "$PHP_INI_DIR/php.ini"

RUN echo "upload_max_filesize = 100M" >> "$PHP_INI_DIR/php.ini" \
    && echo "post_max_size = 100M" >> "$PHP_INI_DIR/php.ini"


# Dependencies
RUN apt-get update -y && apt-get install -y ssh libpng-dev libmagickwand-dev libjpeg-dev libmemcached-dev zlib1g-dev libzip-dev git unzip subversion ca-certificates libicu-dev libxml2-dev libmcrypt-dev && apt-get autoremove -y && apt-get clean && rm -rf /var/lib/apt/lists/

# PHP Extensions - PECL
RUN pecl install imagick-3.4.4 memcached mcrypt-1.0.4 && docker-php-ext-enable imagick memcached mcrypt


# PHP Extensions - docker-php-ext-install
RUN docker-php-ext-install zip gd mysqli exif pdo pdo_mysql opcache intl soap


# PHP Extensions - docker-php-ext-configure
RUN docker-php-ext-configure intl


# PHP Tools
RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && chmod +x wp-cli.phar && mv wp-cli.phar /usr/local/bin/wp
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php --install-dir=/usr/local/bin --filename=composer


RUN a2enmod rewrite headers deflate

WORKDIR /var/www/html


COPY composer.json ./

# RUN composer install --no-scripts --no-autoloader

COPY . ./


# RUN composer dump-autoload --optimize  --no-scripts



RUN rm /etc/apache2/sites-available/000-default.conf && rm /etc/apache2/sites-enabled/000-default.conf
ADD vhost.docker.conf /etc/apache2/sites-available/vhost.docker.conf
RUN a2ensite vhost.docker.conf

RUN chmod -R 777 .




RUN mkdir -p storage/framework/sessions
RUN mkdir -p storage/framework/views
RUN mkdir -p storage/framework/cache
RUN mkdir -p storage/logs
RUN chown -R www-data:www-data storage
RUN chown -R www-data:www-data /var/www/html

RUN mkdir routes/custom


# RUN php artisan migrate:fresh --seed

EXPOSE 80

