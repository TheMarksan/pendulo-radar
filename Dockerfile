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

# Cria o script de inicialização robusto
RUN echo '#!/bin/bash\n\
set +e  # Não para em erros\n\
\n\
echo "================================="\n\
echo "Starting Laravel Application"\n\
echo "================================="\n\
\n\
echo "\n[1/7] Waiting for database connection..."\n\
sleep 5\n\
\n\
echo "\n[2/7] Clearing configuration cache..."\n\
php artisan config:clear 2>&1 || echo "Config clear failed (continuing...)"\n\
\n\
echo "\n[3/7] Testing database connection..."\n\
php artisan db:show 2>&1 || echo "Database connection test failed (continuing...)"\n\
\n\
echo "\n[4/7] Running migrations..."\n\
php artisan migrate --force 2>&1 | while IFS= read -r line; do\n\
  if echo "$line" | grep -q "already exists"; then\n\
    echo "  ⚠️  $line (skipping...)"\n\
  elif echo "$line" | grep -q "FAIL"; then\n\
    echo "  ⚠️  $line (continuing...)"\n\
  else\n\
    echo "  $line"\n\
  fi\n\
done\n\
\n\
echo "\n[5/7] Clearing application cache..."\n\
php artisan cache:clear 2>&1 || echo "Cache clear failed (continuing...)"\n\
php artisan view:clear 2>&1 || echo "View clear failed (continuing...)"\n\
php artisan route:clear 2>&1 || echo "Route clear failed (continuing...)"\n\
\n\
echo "\n[6/7] Optimizing application..."\n\
php artisan config:cache 2>&1 || echo "Config cache failed (continuing...)"\n\
php artisan route:cache 2>&1 || echo "Route cache failed (continuing...)"\n\
php artisan view:cache 2>&1 || echo "View cache failed (continuing...)"\n\
\n\
echo "\n[7/7] Starting Laravel server..."\n\
echo "================================="\n\
echo "Server running on port ${PORT:-10000}"\n\
echo "================================="\n\
\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-10000}\n\
' > /app/start.sh && chmod +x /app/start.sh

# Gera chave da aplicação se não existir
RUN php artisan key:generate || true

# Ajusta permissões
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache || true

# Permite acesso à porta padrão do Render
EXPOSE 10000

# Usa o script de inicialização
CMD ["/app/start.sh"]
