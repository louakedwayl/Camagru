FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    msmtp \
    msmtp-mta \
    ca-certificates \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install mysqli pdo pdo_mysql gd \
    && docker-php-ext-enable mysqli

RUN a2enmod rewrite

COPY msmtprc_template /etc/msmtprc
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod 600 /etc/msmtprc \
    && chown www-data:www-data /etc/msmtprc \
    && chmod +x /usr/local/bin/entrypoint.sh

RUN echo 'sendmail_path = /usr/bin/msmtp -t' > /usr/local/etc/php/conf.d/sendmail.ini

COPY . /var/www/html/

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]