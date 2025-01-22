# Usa una imagen base de PHP con Apache (Debian)
FROM php:8.2-apache

# Instala dependencias necesarias (para PDO y MySQL)
RUN apt-get update && apt-get install -y \
    $PHPIZE_DEPS \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql zip

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establece el directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Copia todos los archivos de la aplicación Laravel
COPY . /var/www/html

# Instala las dependencias de Laravel usando Composer
RUN composer install --no-scripts

# Genera la clave de la aplicación Laravel (puedes descomentar si es necesario)
# RUN php artisan key:generate

# Cambia el propietario de los archivos al usuario www-data
RUN chown -R www-data:www-data /var/www/html

# Establece permisos para la carpeta storage
RUN chmod -R 775 /var/www/html/storage

# Expone el puerto 80
EXPOSE 80

# Configura Apache para que sirva Laravel (modificar el VirtualHost)
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Habilita mod_rewrite para URLs amigables de Laravel
RUN a2enmod rewrite

# Comando por defecto al arrancar el contenedor
CMD ["apache2-foreground"]