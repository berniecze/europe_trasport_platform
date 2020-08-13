FROM php:7.1.22-fpm

ENV PHPREDIS_VERSION=3.1.6

RUN \
 apt-get update && \
 apt-get install -y vim git curl zip unzip && \
# Install PHP extensions
# pdo_mysql
 docker-php-ext-install pdo_mysql && \
# opcache
 docker-php-ext-install opcache && \
# mbstring
 docker-php-ext-install mbstring && \
# bcmath
 docker-php-ext-install bcmath && \
# exif
 docker-php-ext-install exif && \
# zip
 apt-get install -y zlib1g-dev && \
 docker-php-ext-install zip && \
# mongodb
 apt-get install -y libssl-dev && \
 pecl install mongodb && \
 docker-php-ext-enable mongodb && \
# xdebug
 pecl install xdebug && \
 docker-php-ext-enable xdebug && \
# gd
 apt-get install -y libpng-dev && \
 docker-php-ext-install gd && \
# phpredis
mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis && \
# blackfire
#apt-get install -y gnupg && \
#curl -o - https://packagecloud.io/gpg.key | apt-key add - && \
#echo "deb http://packages.blackfire.io/debian any main" > /etc/apt/sources.list.d/blackfire.list && \
#apt-get update && \
#apt-get install blackfire-php && \
# cleanup
 rm -rf \
    /tmp/* \
    /var/lib/apt/lists/* \
    /var/tmp/*

# Set UID & GID for php-fpm user to 1000:1000
RUN \
 usermod -u 1000 www-data && \
 groupmod -g 1000 www-data && \
# enable login for www-data user
 usermod -d /home/www-data -s /bin/bash www-data

# Install composer system-wide
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    chmod +x /usr/local/bin/composer && \
    chown www-data:www-data /usr/local/bin/composer

# Setup environment
ENV DEV=1
RUN mkdir -p /project

WORKDIR /project
