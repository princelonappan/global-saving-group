# Dockerfile
FROM php:7.4-cli

RUN mkdir -p /var/www/html/api/
WORKDIR /var/www/html/api/
COPY . /var/www/html/api/

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

RUN composer install

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony