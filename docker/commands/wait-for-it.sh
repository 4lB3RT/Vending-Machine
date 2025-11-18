#!/bin/sh
# wait-for-it.sh - Wait until a host:port is available
# https://github.com/vishnubob/wait-for-it

set -e

TIMEOUT=60
STRICT=0
HOST=""
PORT=""

while [ $# -gt 0 ]; do
  case "$1" in
    --timeout)
      TIMEOUT="$2"
      shift 2
      ;;
    --strict)
      STRICT=1
      shift
      ;;
    *)
      if [ -z "$HOST" ]; then
        HOST="$1"
      elif [ -z "$PORT" ]; then
        PORT="$1"
      fi
      shift
      ;;
  esac
done

if [ -z "$HOST" ]; then
  echo "Usage: $0 host:port [--timeout seconds] [--strict]"
  exit 1
fi

HOST_PART=`echo $HOST | cut -d: -f1`
PORT_PART=`echo $HOST | cut -d: -f2`

for i in `seq 1 $TIMEOUT`; do
  nc -z "$HOST_PART" "$PORT_PART" && exit 0
  sleep 1
done

if [ "$STRICT" -eq 1 ]; then
  echo "Timeout waiting for $HOST_PART:$PORT_PART"
  exit 1
fi
exit 0
