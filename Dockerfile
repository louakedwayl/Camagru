# 1. Image de base
FROM php:8.2-apache

# 2. Installation des dépendances Système (Email + Extensions PHP)
# On installe msmtp pour l'envoi de mail
RUN apt-get update && apt-get install -y \
    msmtp \
    msmtp-mta \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# 3. Installation des extensions PHP requises (PDO)
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli

# 4. Configuration Apache
RUN a2enmod rewrite

# --- CONFIGURATION EMAIL SÉCURISÉE ---

# Copie du modèle de config (le squelette)
COPY msmtprc_template /etc/msmtprc

# Copie du script de démarrage (le robot)
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# Permissions strictes (nécessaires pour msmtp)
RUN chmod 600 /etc/msmtprc \
    && chown www-data:www-data /etc/msmtprc \
    && chmod +x /usr/local/bin/entrypoint.sh

# Configuration PHP pour utiliser msmtp
RUN echo 'sendmail_path = /usr/bin/msmtp -t' > /usr/local/etc/php/conf.d/sendmail.ini

# -------------------------------------

# On le fait à la fin pour profiter du cache Docker
COPY . /var/www/html/

# 6. Port
EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]