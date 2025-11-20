#!/bin/sh
set -e

PROJECT_ROOT="$(cd "$(dirname "$0")/../.." && pwd)"

spinner() {
  local pid=$1
  local delay=0.1
  local spinstr='|/-\\'
  while kill -0 $pid 2>/dev/null; do
    local temp=${spinstr#?}
    printf " [%c] Preparing environment...\r" "$spinstr"
    spinstr=$temp${spinstr%$temp}
    sleep $delay
  done
  printf "    \r"
}

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

(
  docker rm -f "$MYSQL_HOST" > /dev/null 2>&1 || true
  docker rm -f "$PHP_HOST" > /dev/null 2>&1 || true
  docker rm -f "$REDIS_HOST" > /dev/null 2>&1 || true
  docker network rm "$NETWORK" > /dev/null 2>&1 || true
  rm -rf "$MYSQL_VOLUME"

  docker network create "$NETWORK" > /dev/null 2>&1 || true

  docker run -d --name "$MYSQL_HOST" --network "$NETWORK" \
    -e MYSQL_DATABASE="$MYSQL_DATABASE" \
    -e MYSQL_USER="$MYSQL_USER" \
    -e MYSQL_PASSWORD="$MYSQL_PASSWORD" \
    -e MYSQL_ROOT_PASSWORD="$MYSQL_PASSWORD" \
    -v "$MYSQL_VOLUME":/var/lib/mysql \
    -p "$MYSQL_PORT":3306 \
    mariadb:latest \
    --default-authentication-plugin=mysql_native_password > /dev/null 2>&1

  docker run -d --name "$REDIS_HOST" --network "$NETWORK" \
    -p "$REDIS_PORT":6379 \
    redis:latest > /dev/null 2>&1

  if ! docker image inspect vending-machine-php:latest > /dev/null 2>&1; then
    docker build -t vending-machine-php:latest "$PROJECT_ROOT/docker/php" > /dev/null 2>&1
  fi

  docker run -d --name "$PHP_HOST" --network "$NETWORK" \
    -v "$PROJECT_ROOT":/code \
    -v "$PROJECT_ROOT/.env.testing":/code/.env \
    -w /code \
    vending-machine-php:latest tail -f /dev/null > /dev/null 2>&1

  chmod +x ./docker/commands/wait-for-it.sh
  docker exec "$PHP_HOST" /code/docker/commands/wait-for-it.sh "$MYSQL_HOST:3306" --timeout=60 --strict -- echo "MariaDB is up" > /dev/null 2>&1
) &
spinner $!

if ! docker ps -a --format '{{.Names}}' | grep -q "^$PHP_HOST$"; then
  echo "[ERROR] PHP container '$PHP_HOST' was not created. Aborting tests." >&2
  exit 1
fi

DOCKER_EXEC_FLAGS=""
if [ "$CI" = "true" ]; then
  DOCKER_EXEC_FLAGS="-it"
fi

echo "Running tests..."
docker exec $DOCKER_EXEC_FLAGS "$PHP_HOST" php artisan migrate
docker exec $DOCKER_EXEC_FLAGS "$PHP_HOST" composer test:integration

(
  # Stop and remove containers
  docker rm -f "$MYSQL_HOST" > /dev/null 2>&1 || true
  docker rm -f "$PHP_HOST" > /dev/null 2>&1 || true
  docker rm -f "$REDIS_HOST" > /dev/null 2>&1 || true
  docker network rm "$NETWORK" > /dev/null 2>&1 || true
  rm -rf "$MYSQL_VOLUME"
  for c in "$MYSQL_HOST" "$PHP_HOST" "$REDIS_HOST"; do
    for v in $(docker volume ls -q); do
      if ! docker ps -a --format '{{.Mounts}}' | grep -q "$v"; then
        docker volume rm "$v" > /dev/null 2>&1 || true
      fi
    done
  done
) &
spinner $!
