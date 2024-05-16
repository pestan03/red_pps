# Imagen base
FROM php:8.2-apache

# Etiqueta del mantenedor
LABEL maintainer="Tu Nombre <tu_email@example.com>"

# Instalar MySQL
RUN apt-get update && apt-get install -y \
    mysql-server \
    && rm -rf /var/lib/apt/lists/*

# Instalar PHP extensiones necesarias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Configurar Apache (si es necesario)
# Por ejemplo, si necesitas activar el módulo rewrite, puedes hacerlo con:
# RUN a2enmod rewrite

# Copiar los archivos de configuración y la aplicación si es necesario
# COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
# COPY . /var/www/html

# Exponer puertos
EXPOSE 80
EXPOSE 3306

# Comando de inicio
CMD ["apache2-foreground"]
