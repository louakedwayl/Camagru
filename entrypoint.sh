#!/bin/bash
set -e

# --- CONFIGURATION EMAIL ---
echo "Configuration de msmtp..."
sed -i "s/EMAIL_PLACEHOLDER/$EMAIL/g" /etc/msmtprc
sed -i "s/EMAILPASS_PLACEHOLDER/$EMAILPASS/g" /etc/msmtprc

# --- FIX DES PERMISSIONS ---
echo "Configuration des dossiers d'upload..."
mkdir -p /var/www/html/public/uploads/posts
mkdir -p /var/www/html/public/uploads/avatars
# On donne la propriété à l'utilisateur d'Apache (www-data)
chown -R www-data:www-data /var/www/html/public/uploads
# Permissions (755 est suffisant et plus sécurisé que 777)
chmod -R 755 /var/www/html/public/uploads

# Permissions pour les avatars du setup
chown -R www-data:www-data /var/www/html/assets/images/avatars
chmod -R 755 /var/www/html/assets/images/avatars

echo "Permissions OK."

# --- LANCEMENT D'APACHE (UNE SEULE FOIS À LA FIN) ---
exec docker-php-entrypoint apache2-foreground