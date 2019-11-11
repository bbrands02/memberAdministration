###################################################################
## 			Lets first setup our basic apache server			 ##
###################################################################

# Use the official php library https://hub.docker.com/_/php/ coming directly with apache
FROM php:7.3.1-apache
		


# Custom Conf
COPY php.ini /usr/local/etc/php/

# Add mango DB ot the trusted sources
RUN apt-get update && apt-get install -y gnupg
RUN apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 9DA31620334BD75D9DCB49F368818C72E52529D4

# Install git & Request system packages
RUN apt-get update && apt-get install --no-install-recommends -y \
	git \
	curl \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \ 
    libpq-dev \
    zlib1g-dev \
    libzip-dev\
    wget \
    git \
    unzip \
    gettext \
    imagemagick \
    acl \
    file \
    openssh-server \
    openssh-client \
    zip \
    nano \
    cron \
    ack-grep \
    pkg-config \
&& rm -rf /var/lib/apt/lists/*

# Install php extensions
RUN apt-get update && apt-get install libgmp-dev -y && \
    apt-get update && apt-get install -y libgmp-dev re2c libmhash-dev libmcrypt-dev file && \
    docker-php-ext-configure gmp && docker-php-ext-install gmp
	
RUN docker-php-ext-configure zip --with-libzip; 

RUN docker-php-ext-install \
		pdo_mysql \
	&& \
	docker-php-ext-enable \
		pdo_mysql 
		
# Installing composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
# install Symfony Flex globally to speed up download of Composer packages (parallelized prefetching)
RUN set -eux; \
	composer global require "symfony/flex" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

# Enabling module rewrite
RUN a2enmod rewrite




###################################################################
## 		Then lets's do the taartenwinkel specifc stuff			 ##
###################################################################

# RUN mkdir /var/www
WORKDIR /var/www

COPY public public/
COPY vendor vendor/
COPY composer.json ./

# Lets take a moment to actually run composer


###################################################################
## 			Then we need to activate the webs server			 ##
###################################################################

# Lets spin up apache
COPY 000-default.conf /etc/apache2/sites-available