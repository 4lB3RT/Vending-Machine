#!/bin/sh
set -e

# Definir variables primero
PROJECT_NAME="${PROJECT_NAME:-vending_machine}"
MYSQL_HOST="mysql_${PROJECT_NAME}_test"
PHP_HOST="php_${PROJECT_NAME}_test"
NETWORK="vending_machine_test"
MYSQL_PORT="13306"
MYSQL_VOLUME="/tmp/mysqltest"
MYSQL_USER="${MYSQL_USER:-testuser}"
MYSQL_PASSWORD="${MYSQL_PASSWORD:-testpass}"
MYSQL_DATABASE="${MYSQL_DATABASE:-vending_machine_test}"
REDIS_HOST="redis_${PROJECT_NAME}_test"
REDIS_PORT="16379"

# Eliminar contenedores, red y volumen previos si existen

docker rm -f "$MYSQL_HOST" || true
docker rm -f "$PHP_HOST" || true
docker rm -f "$REDIS_HOST" || true
docker network rm "$NETWORK" || true
rm -rf "$MYSQL_VOLUME"

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

# Redis container
docker run -d --name "$REDIS_HOST" --network "$NETWORK" \
  -p "$REDIS_PORT":6379 \
  redis:latest

PROJECT_ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
docker run -d --name "$PHP_HOST" --network "$NETWORK" \
  -v "$PROJECT_ROOT":/code \
  -w /code \
  vending-machine-php:latest tail -f /dev/null

# Copiar .env.testing a .env dentro del contenedor PHP
# Esto asegura que Laravel use la configuración de integración

docker exec "$PHP_HOST" cp /code/.env.testing /code/.env

# Esperar a que MariaDB esté listo usando wait-for-it.sh
chmod +x ./docker/commands/wait-for-it.sh

docker exec "$PHP_HOST" /code/docker/commands/wait-for-it.sh "$MYSQL_HOST:3306" --timeout=60 --strict -- echo "MariaDB is up"

DOCKER_EXEC_FLAGS=""
if [ "$CI" = "true" ]; then
  DOCKER_EXEC_FLAGS="-it"
fi

docker exec $DOCKER_EXEC_FLAGS "$PHP_HOST" php artisan migrate
docker exec $DOCKER_EXEC_FLAGS "$PHP_HOST" composer test:integration

# Eliminar contenedores, red y volumen al finalizar

docker rm -f "$MYSQL_HOST" || true
docker rm -f "$PHP_HOST" || true
docker rm -f "$REDIS_HOST" || true
docker network rm "$NETWORK" || true
rm -rf "$MYSQL_VOLUME"
