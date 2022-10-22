FROM php:8.1.6-fpm

ARG DEBIAN_FRONTEND=noninteractive
ARG LOCAL_UID=$LOCAL_UID
ARG LOCAL_GID=$LOCAL_GID

WORKDIR $WORKING_DIR

#install git
RUN apt update \
  && apt -y install git \
  && apt clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*;

RUN apt update \
  && apt install -y libicu-dev libpng-dev libjpeg-dev libpq-dev libonig-dev zip \
  && apt clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*; 

RUN docker-php-ext-configure gd --with-jpeg \
  && docker-php-ext-install intl gd mbstring; 

RUN pecl install xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer;

RUN groupadd -g $LOCAL_GID developer \
  && useradd -d /home/developer -s /bin/bash -u $LOCAL_UID -g $LOCAL_GID developer \
  && mkdir /home/developer && chown -R developer . /home/developer;

USER root

RUN usermod -u $LOCAL_UID developer && groupmod -g $LOCAL_GID developer

USER developer
