#!/bin/sh

# No encuentro manera de parar el server al para el contenedor. Borrar el directorio .symfony5 lo soluciona
rm -rf ~/.symfony5

cd /var/www/html 

# composer install solo sería necesario la primera vez, pero este entyrypoint no es tan fino:
composer install 

symfony server:start