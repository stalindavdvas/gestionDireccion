# Usar una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Copiar los archivos del proyecto al contenedor
COPY . /var/www/html/

# Asignar permisos adecuados
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Iniciar Apache en segundo plano
CMD ["apache2-foreground"]
