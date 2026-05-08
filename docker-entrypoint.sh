#!/bin/bash
set -e

echo "Iniciando entrypoint do Laravel..."

if [ -n "$DB_HOST" ] && [ -n "$DB_DATABASE" ] && [ -n "$DB_USERNAME" ]; then
  echo "Variáveis de banco configuradas. Aguardando conexão..."

  retries=0
  until php artisan migrate:status --no-interaction >/dev/null 2>&1 || [ "$retries" -ge 20 ]; do
    ((retries++))
    echo "Banco não disponível. Tentando novamente em 3s ($retries/20)..."
    sleep 3
  done

  if [ "$retries" -lt 20 ]; then
    echo "Banco disponível. Executando migrations..."
    if php artisan migrate --force --no-interaction; then
      echo "Migrations executadas com sucesso."
    else
      echo "Erro ao executar migrations, mas continuando inicialização."
    fi
  else
    echo "Banco não ficou disponível após aguardar. Continuando sem migrations."
  fi
else
  echo "Variáveis de banco não configuradas. Pulando migrations."
fi

echo "Iniciando aplicação..."
exec "$@"
