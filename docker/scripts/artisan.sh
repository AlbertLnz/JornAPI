#!/bin/bash
echo "Ejecutando comandos de Artisan..."

php /var/www/html/artisan cache:clear
php /var/www/html/artisan config:clear
php /var/www/html/artisan route:clear
php /var/www/html/artisan db:seed 
echo "Comandos de Artisan ejecutados."
