FROM php:8.4.6-fpm-bookworm
WORKDIR "/application"

ARG WWWUSER=1000
ARG WWWGROUP=1000

# Install system and PHP dependencies
RUN apt-get update && \
    apt-get install -y \
        git curl rsync unzip openssh-client \
        libzip-dev libicu-dev libpng-dev libjpeg-dev libwebp-dev libxpm-dev \
        libfreetype6-dev libxml2-dev libonig-dev imagemagick libmagickwand-dev \
        gettext-base build-essential autoconf bash && \
    docker-php-ext-configure intl && \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    pecl install imagick redis && \
    docker-php-ext-enable imagick redis && \
    docker-php-ext-install pdo_mysql zip pcntl intl bcmath gd && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*



# Install Composer (legfrissebb stabil) biztonságosan
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "copy('https://composer.github.io/installer.sig', 'composer-setup.sig');" && \
    php -r "if (trim(file_get_contents('composer-setup.sig')) !== hash_file('sha384', 'composer-setup.php')) { echo 'Invalid installer'.PHP_EOL; unlink('composer-setup.php'); exit(1); }" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php'); unlink('composer-setup.sig');"

# Set www-data user with the host user/group ID
RUN usermod -u $WWWUSER www-data && groupmod -g $WWWGROUP www-data

# PsySH configuration to allow running Tinker as a non-root user
RUN mkdir -p /var/www/.psysh \
    && chown 1000:0 /var/www/.psysh \
    && chmod 770 /var/www/.psysh

# Allow Deployer to use /var/www/.ssh during deployment
RUN mkdir -p /var/www/.ssh \
    && chown 1000:0 /var/www/.ssh \
    && chmod 770 /var/www/.ssh

# Clean up build packages and caches
RUN apt-get purge -y build-essential autoconf && \
    apt-get autoremove -y && \
    rm -rf /tmp/* /var/lib/apt/lists/*

