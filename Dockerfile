FROM php:8.2-apache
# Install your extensions to connect to MySQL and add mysqli
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli
