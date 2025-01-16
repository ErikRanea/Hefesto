# Usa una imagen base de PHP con FPM y Alpine
FROM php:8.3-fpm-alpine

# Instala dependencias necesarias (para PDO y MySQL)
RUN apk add --no-cache $PHPIZE_DEPS \
    && docker-php-ext-install pdo pdo_mysql

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establece el directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Copia todos los archivos de la aplicación Laravel
COPY . /var/www/html

# Instala las dependencias de Laravel usando Composer
RUN composer install --no-interaction --optimize-autoloader

# Genera la clave de la aplicación Laravel
#RUN php artisan key:generate

# Cambia el propietario de los archivos al usuario www-data
RUN chown -R www-data:www-data /var/www/html

# Ejecuta la aplicación como el usuario www-data
USER www-data

# Expone el puerto 9000
EXPOSE 8000

# Comando para iniciar el servicio php-fpm
CMD ["php-fpm"]
