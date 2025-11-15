# Dockerfile para Laravel 10/11/12 + MySQL
FROM php:8.2-cli

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl \
    libzip-dev libssl-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copia apenas os arquivos de dependências primeiro (melhor cache)
COPY composer.json composer.lock ./

# Instala dependências do Laravel
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copia o resto da aplicação
COPY . .

# Completa a instalação com scripts
RUN composer dump-autoload --optimize

# Cria o script de inicialização
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Waiting for database connection..."\n\
sleep 5\n\
\n\
echo "Clearing configuration cache..."\n\
php artisan config:clear || true\n\
\n\
echo "Running migrations..."\n\
php artisan migrate --force\n\
\n\
echo "Clearing application cache..."\n\
php artisan cache:clear || true\n\
php artisan view:clear || true\n\
\n\
echo "Caching configuration..."\n\
php artisan config:cache || true\n\
php artisan route:cache || true\n\
\n\
echo "Starting Laravel server..."\n\
php artisan serve --host=0.0.0.0 --port=${PORT:-10000}\n\
' > /app/start.sh && chmod +x /app/start.sh

# Gera chave da aplicação se não existir
RUN php artisan key:generate || true

# Ajusta permissões
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Permite acesso à porta padrão do Render
EXPOSE 10000

# Usa o script de inicialização
CMD ["/app/start.sh"]
