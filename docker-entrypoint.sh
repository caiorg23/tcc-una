#!/bin/bash
set -e

if [ -n "$DB_HOST" ] && [ -n "$DB_DATABASE" ] && [ -n "$DB_USERNAME" ]; then
  echo "Aguardando o banco de dados ficar disponível..."
  retries=0
  until php artisan migrate:status --no-interaction >/dev/null 2>&1 || [ "$retries" -ge 20 ]; do
    ((retries++))
    echo "Banco não disponível ainda. Tentando novamente em 3s ($retries/20)..."
    sleep 3
  done

  if [ "$retries" -lt 20 ]; then
    echo "Executando migrations..."
    php artisan migrate --force --no-interaction
  else
    echo "Banco de dados não ficou disponível após aguardar. Continuando inicialização."
  fi
fi

exec "$@"
