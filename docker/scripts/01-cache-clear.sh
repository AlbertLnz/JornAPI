#!/bin/bash
echo "Ejecutando comandos de Artisan..."
php /var/www/html/artisan migrate --force
php /var/www/html/artisan cache:clear
php /var/www/html/artisan config:clear
