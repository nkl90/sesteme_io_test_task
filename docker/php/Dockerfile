FROM php:8.1-fpm-buster
RUN apt-get update && apt-get install -y \
      git \
      unzip \
      zlib1g-dev \
      libpng-dev \
      libzip-dev \
      libjpeg-dev \
      libpq-dev \
      libicu-dev \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

#COPY ./docker/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
#COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/php.ini
#COPY . /app
RUN EXPECTED_SIGNATURE=$(curl https://composer.github.io/installer.sig) \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');") \
    && if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]; \
    then \
        >&2 echo 'ERROR: Invalid installer signature' \
        && rm -f composer-setup.php \
        && exit 1; \
    fi \
    && php composer-setup.php --quiet \
    && RESULT=$? \
    && rm composer-setup.php \
    && mv composer.phar /usr/local/bin/composer \
    && exit $RESULT
