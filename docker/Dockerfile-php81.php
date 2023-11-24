FROM php:8.1-fpm-alpine as base

#RUN apk update && apk add git zip curl-dev pkgconf openssl-dev libxml2-dev autoconf g++ make npm

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

RUN apk add --no-cache bash
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash
RUN apk add symfony-cli

# Hacer limpieza
RUN apk del curl-dev pkgconf openssl-dev libxml2-dev autoconf g++ make linux-headers
RUN rm -rf /var/cache/apk

FROM php:8.1-fpm-alpine
COPY --from=base / /

ENV HOME /home/user
WORKDIR /var/www/html
RUN mkdir -p /home/user && chmod 777 /home/user
COPY ./entrypoint.sh /
RUN chmod +x /entrypoint.sh
ENTRYPOINT [ "/entrypoint.sh" ]