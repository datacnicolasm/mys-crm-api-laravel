# Usa una imagen base oficial de PHP con Apache
FROM php:8.1-apache

# Establece el directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Instala extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    zip \
    unzip \
    gnupg \
    apt-transport-https \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Instalación de controladores de SQL Server 2017 para PHP
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
    && curl https://packages.microsoft.com/config/debian/9/prod.list > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql17 unixodbc-dev \
    && apt-get install -y libgssapi-krb5-2 \
    && pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Habilita el módulo de reescritura de Apache
RUN a2enmod rewrite

# Copia el archivo de configuración de Apache para Laravel
COPY .docker/ports.conf /etc/apache2/ports.conf
COPY .docker/vhost.conf /etc/apache2/sites-enabled/000-default.conf
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Elimina cualquier configuración existente y crea un enlace simbólico en el directorio sites-enabled
RUN rm /etc/apache2/sites-enabled/000-default.conf \
    && ln -s /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-enabled/000-default.conf

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia el código del proyecto al contenedor
COPY . .

# Instala las dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Ajusta permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Establece las variables de entorno necesarias para Laravel
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV APP_KEY=base64:eMV8FDniphPOpZhoQKLfOjfJzPEOP09DQv4GrX0VSX0=

# Expone el puerto 8080 que utilizará Cloud Run
EXPOSE 8080

# Configura el entorno de Apache para que escuche en el puerto 8080
#RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost *:80>/<VirtualHost *:8080>/' /etc/apache2/sites-enabled/000-default.conf
RUN sed -i 's/<VirtualHost *:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default.conf

# Comando por defecto para ejecutar Apache en modo foreground
CMD ["apache2-foreground"]
