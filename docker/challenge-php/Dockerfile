FROM php:8.1.7-fpm-buster

RUN apt-get -y update
RUN apt-get -y upgrade

#####################################
# INSTALL AND ENABLE PHP EXTENSIONS #
#####################################

RUN apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl opcache mysqli pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
    && docker-php-ext-enable pdo_mysql \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install sockets

###############################
# INSTALL PHP REDIS EXTENSION #
###############################

RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

################
# ADD COMPOSER #
################

COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install pre-reqs
RUN apt-get update
RUN apt-get -y install nano curl build-essential cron

#############################################################################
# SETUP GIT CREDENTIALS TO ALLOW COMPOSER TO PERFORM SOME TASKS #
#############################################################################

RUN git config --global user.email "marciofiorenza@gmail.com" \
    && git config --global user.name "Márcio Fiorenza"

WORKDIR /var/www

CMD php-fpm