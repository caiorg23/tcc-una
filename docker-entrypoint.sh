#!/bin/bash

echo "Iniciando entrypoint do Laravel..."

# Se variáveis de banco estão configuradas, aguarda conexão e executa migrations
if [ -n "$DB_HOST" ] && [ -n "$DB_DATABASE" ] && [ -n "$DB_USERNAME" ]; then
  echo "Variáveis de banco configuradas. Aguardando conexão..."

  retries=0
  max_retries=30
  
  # Aguarda o banco ficar disponível
  while [ "$retries" -lt "$max_retries" ]; do
    if php artisan tinker --no-interaction <<< "exit;" >/dev/null 2>&1; then
      echo "Banco disponível!"
      break
    fi
    ((retries++))
    echo "Tentativa $retries/$max_retries: banco ainda não disponível, aguardando..."
    sleep 2
  done

  if [ "$retries" -lt "$max_retries" ]; then
    echo "Banco disponível. Executando migrations..."
    php artisan migrate --force --no-interaction
    echo "Migrations executadas com sucesso."
  else
    echo "Aviso: Banco não ficou disponível após aguardar $max_retries tentativas."
  fi
else
  echo "Variáveis de banco não configuradas. Pulando migrations."
fi

echo "Iniciando aplicação..."
exec "$@"
