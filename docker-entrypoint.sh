#!/bin/bash

echo "Iniciando entrypoint do Laravel..."

# Se variáveis de banco estão configuradas (DATABASE_URL ou DB_HOST), aguarda conexão e executa migrations
if [ -n "$DATABASE_URL" ] || ([ -n "$DB_HOST" ] && [ -n "$DB_DATABASE" ] && [ -n "$DB_USERNAME" ]); then
  echo "Variáveis de banco configuradas. Aguardando conexão..."

  retries=0
  max_retries=30
  
  # Aguarda o banco ficar disponível
  while [ "$retries" -lt "$max_retries" ]; do
    if php -r "
      try {
        \$pdo = new PDO(getenv('DATABASE_URL') ?: 'pgsql:host='.getenv('DB_HOST').';port='.getenv('DB_PORT').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
        echo 'connected';
      } catch (Exception \$e) {
        exit(1);
      }
    " 2>/dev/null | grep -q "connected"; then
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
    
    echo "Populating services..."
    php artisan db:seed --class=ServiceSeeder --force
    echo "Services seeded successfully."
  else
    echo "Aviso: Banco não ficou disponível após aguardar $max_retries tentativas."
    echo "Tentando executar migrations mesmo assim..."
    php artisan migrate --force --no-interaction || echo "Migrations falharam, mas continuando..."
    php artisan db:seed --class=ServiceSeeder --force || echo "Seeding failed, but continuing..."
  fi
else
  echo "Variáveis de banco não configuradas. Pulando migrations."
fi

echo "Iniciando aplicação..."
exec "$@"
