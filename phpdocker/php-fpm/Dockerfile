FROM phpdockerio/php72-fpm:latest
WORKDIR "/application"

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.2-pgsql \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

ENV \
    APACHE_ADMIN_EMAIL=webmaster@localhost \
    PHP_TIME_ZONE=Europe/London \
    PHP_MEMORY_LIMIT=2G \
    PHP_UPLOAD_MAX_FILESIZE=32M \
    PHP_POST_MAX_SIZE=32M

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*