FROM php:7-apache

# set PATH for Wordpress CLI
ENV PATH="~/.composer/vendor/bin:${PATH}"

# reduce APT noise
ENV DEBIAN_FRONTEND=noninteractive

# use proper shell
SHELL ["/bin/bash", "-c"]

# Enable stderr error logging for PHP
RUN echo 'log_errors = On' > /usr/local/etc/php/conf.d/log_errors.ini
RUN echo 'error_log = /dev/stderr' >> /usr/local/etc/php/conf.d/log_errors.ini

# to avoid all too common aborts because of debian repo timeouts
RUN echo 'APT::Acquire::Retries "30";' > /etc/apt/apt.conf.d/80-retries

# install npm nodesource repo
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash -

# upgrade package list and default packages
RUN apt-get -qq update
RUN apt-get -qq upgrade

# install php extension dev dependencies
RUN apt-get -qq install libmemcached-dev libfreetype-dev libzip-dev zlib1g-dev libxml2-dev libjpeg-dev libpng-dev libonig-dev libtidy-dev

# install basic tools
RUN apt-get -qq install nodejs git unzip vim wget mariadb-client zip jq

# clean up to reduce docker image size
RUN apt-get -qq autoremove

# install PHP extensions required
RUN bash -c "pecl install xdebug memcached &> /dev/null"
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN bash -c "docker-php-ext-install -j5 gd soap zip mbstring mysqli pdo pdo_mysql tidy bcmath &> /dev/null"
RUN docker-php-ext-enable soap zip memcached xdebug gd mbstring mysqli pdo pdo_mysql tidy bcmath

# enable apache modules
RUN a2enmod rewrite headers ext_filter expires

# create self-signed cert and enable SSL on apache
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/ssl-cert-snakeoil.key -out /etc/ssl/certs/ssl-cert-snakeoil.pem -subj "/C=AT/ST=Vienna/L=Vienna/O=Security/OU=Development/CN=example.com"
RUN a2ensite default-ssl
RUN a2enmod ssl

# get composer binary from composer docker image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# install ngrok
COPY --from=ngrok/ngrok:2-debian /bin/ngrok /usr/bin/ngrok

# copy ngrok script
COPY docker/oxid/ngrok.sh /usr/local/bin/ngrok.sh

# override entrypoint
# copy entrypoint script
COPY docker/oxid/init.sh /usr/local/bin/init.sh

# make scripts executable
RUN chmod +x /usr/local/bin/*.sh

# override default entrypoin with ours
ENTRYPOINT [ "init.sh" ]

# add user and dir for executing composer
RUN useradd -u 431 -r -g www-data -s /sbin/nologin -c "oxid user" oxid
RUN mkdir -p /home/oxid && chown -R oxid:www-data /home/oxid /var/www/html /etc/ssl
COPY docker/oxid/config.inc.php /home/oxid/config.inc.php
# oxid needs to write to parent directory of shop
RUN chown -R oxid:www-data /var/www /home/oxid/config.inc.php
USER oxid
WORKDIR /home/oxid

# copy module
COPY modules /modules

RUN mkdir /tmp/oxid-data
WORKDIR /tmp/oxid-data

WORKDIR /var/www

EXPOSE 80
EXPOSE 443
