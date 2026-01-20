FROM php:8.4-cli

# Instalar dependencias del sistema y Node.js actualizado
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /app

# Copiar archivos de composer primero
COPY composer.json composer.lock ./

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copiar el resto del proyecto
COPY . .

# Instalar dependencias npm y compilar assets
RUN npm install && npm run build

# Permisos
RUN chmod -R 775 storage bootstrap/cache

# Exponer puerto
EXPOSE 8080

# Comando de inicio
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
