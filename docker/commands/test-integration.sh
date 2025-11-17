#!/bin/sh
set -e

# Definir variables primero
PROJECT_NAME="${PROJECT_NAME:-vending_machine}"
MYSQL_HOST="mysql_${PROJECT_NAME}_test"
PHP_HOST="php_${PROJECT_NAME}_test"
NETWORK="vending_machine_test"
MYSQL_PORT="${MYSQL_PORT:-3306}"
MYSQL_VOLUME="/tmp/mysqltest"
MYSQL_USER="${MYSQL_USER:-testuser}"
MYSQL_PASSWORD="${MYSQL_PASSWORD:-testpass}"
MYSQL_DATABASE="${MYSQL_DATABASE:-vending_machine_test}"

# Eliminar contenedores y red previos si existen

docker rm -f "$MYSQL_HOST" || true
docker rm -f "$PHP_HOST" || true
docker network rm "$NETWORK" || true

if [ -f "../../.env" ]; then
  export $(grep -v '^#' ../../.env | xargs)
fi

# Crear red
docker network create "$NETWORK" || true

# Crear contenedores
docker run -d --name "$MYSQL_HOST" --network "$NETWORK" \
  -e MYSQL_DATABASE="$MYSQL_DATABASE" \
  -e MYSQL_USER="$MYSQL_USER" \
  -e MYSQL_PASSWORD="$MYSQL_PASSWORD" \
  -e MYSQL_ROOT_PASSWORD="$MYSQL_PASSWORD" \
  -v "$MYSQL_VOLUME":/var/lib/mysql \
  -p "$MYSQL_PORT":3306 \
  mariadb:latest \
  --default-authentication-plugin=mysql_native_password

PROJECT_ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
docker run -d --name "$PHP_HOST" --network "$NETWORK" \
  -v "$PROJECT_ROOT":/code \
  -w /code \
  vending-machine-php:latest tail -f /dev/null

docker exec -it "$PHP_HOST" php artisan migrate --env=testing
docker exec -it "$PHP_HOST" composer test:integration
