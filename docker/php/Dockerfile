FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www

# Copiar archivo de configuración PHP personalizado (opcional)
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Exponer puerto (aunque no es necesario en FPM)
EXPOSE 9000

CMD ["php-fpm"]
