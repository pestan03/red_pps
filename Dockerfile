# Imagen base
FROM php:8.2-apache

# Etiqueta del mantenedor
LABEL maintainer="Tu Nombre <tu_email@example.com>"

# Instalar MySQL
# COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
# COPY . /var/www/html

# Exponer puertos
EXPOSE 80
EXPOSE 3306

# Comando de inicio
CMD ["apache2-foreground"]
