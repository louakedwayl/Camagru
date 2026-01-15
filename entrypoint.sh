#!/bin/bash
set -e

# On remplace les placeholders par tes variables du .env
# Les guillemets sont importants pour gérer les espaces dans ton mot de passe
sed -i "s/EMAIL_PLACEHOLDER/$EMAIL/g" /etc/msmtprc
sed -i "s/EMAILPASS_PLACEHOLDER/$EMAILPASS/g" /etc/msmtprc

# On lance Apache
exec docker-php-entrypoint apache2-foreground