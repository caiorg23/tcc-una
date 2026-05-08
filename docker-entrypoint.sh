#!/bin/bash
set -e

echo "Iniciando entrypoint do Laravel..."

if [ -n "$DB_HOST" ] && [ -n "$DB_DATABASE" ] && [ -n "$DB_USERNAME" ]; then
  echo "Variáveis de banco configuradas. Aguardando conexão..."

  retries=0
  until php artisan migrate:status --no-interaction >/tmp/migrate_status.log 2>&1 || [ "$retries" -ge 20 ]; do
    ((retries++))
    echo "Tentativa $retries/20: banco ainda não disponível."
    tail -n 20 /tmp/migrate_status.log || true
    sleep 3
  done

  if [ "$retries" -lt 20 ]; then
    echo "Banco disponível. Executando migrations..."
    if php artisan migrate --force --no-interaction 2>&1 | tee /tmp/migrate_run.log; then
      echo "Migrations executadas com sucesso."
    else
      echo "Erro ao executar migrations, mas continuando inicialização."
      tail -n 50 /tmp/migrate_run.log || true
    fi
  else
    echo "Banco não ficou disponível após aguardar. Continuando sem migrations."
    tail -n 50 /tmp/migrate_status.log || true
  fi
else
  echo "Variáveis de banco não configuradas. Pulando migrations."
fi

echo "Iniciando aplicação..."
exec "$@"
