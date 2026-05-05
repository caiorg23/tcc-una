#!/bin/bash

# Instalar dependências PHP
composer install --no-dev --optimize-autoloader

# Copiar .env se não existir
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Gerar chave da aplicação
php artisan key:generate --force

# Instalar dependências Node
npm install

# Compilar assets
npm run production

# Executar migrations (opcional, descomente se tiver DB)
# php artisan migrate --force
