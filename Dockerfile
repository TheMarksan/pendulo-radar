# Dockerfile para Laravel 10/11/12 + MySQL
FROM php:8.2-cli

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl \
    libzip-dev libssl-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instala Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Instala dependências do Laravel
RUN composer install --no-dev --optimize-autoloader

# Gera chave da aplicação se não existir
RUN php artisan key:generate || true

# Permite acesso à porta padrão do Render
EXPOSE 10000

# Comando para iniciar o servidor Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
