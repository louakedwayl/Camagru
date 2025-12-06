# Utilise l'image PHP avec Apache
FROM php:8.2-apache

# Active les extensions PHP courantes pour Camagru
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli

# Copie ton projet dans le dossier web d'Apache
COPY . /var/www/html/

# Expose le port 80
EXPOSE 80

# Active mod_rewrite pour Apache (utile pour Camagru)
RUN a2enmod rewrite

