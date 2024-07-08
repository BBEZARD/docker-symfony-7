FROM php:8.3-fpm

RUN set -eux; \
    \
# Adding Symfony repository
	echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list; \
# Install common packages
    apt-get update; \
    apt-get install -y --no-install-recommends \
        nginx \
        git \
        symfony-cli \
    # Install supervisor pour lancer Nginx et PHP-FPM
        supervisor \
# Install PHP extensions
        libcurl4-gnutls-dev \
        libfreetype6-dev \
        libicu-dev \
        libpng-dev \
        libxml2-dev \
        libzip-dev \
        zlib1g-dev libpng-dev libjpeg-dev libfreetype6-dev \
    ; \
    docker-php-ext-configure \
        intl \
    ; \
    docker-php-ext-configure \
        gd --with-freetype --with-jpeg \
    ; \
    docker-php-ext-install \
        curl \
        intl \
        gd \
        pdo_mysql \
        opcache \
        xml \
        zip \
        exif \
    ;

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash -; \
    apt-get install -y nodejs; \
# Install yarn
RUN npm install -g yarn; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

RUN echo "[supervisord]\nnodaemon=true\n\n[program:php-fpm]\ncommand=/usr/local/sbin/php-fpm\n\n[program:nginx]\ncommand=/usr/sbin/nginx -g 'daemon off;'" > /etc/supervisor/conf.d/supervisord.conf

COPY ./build/web/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
COPY ./build/web/usr/local/etc/php/conf.d/php.ini /usr/local/etc/php/conf.d

WORKDIR /var/www

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]