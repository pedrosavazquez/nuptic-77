FROM mariadb:10.6.2

ARG LOCAL_UID=$LOCAL_UID
ARG LOCAL_GID=$LOCAL_GID

RUN usermod -u ${LOCAL_UID:-999} mysql && groupmod -g ${LOCAL_GID:-999} mysql
