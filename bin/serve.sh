#!/bin/bash
# Start (or restart) the mdgoatco dev server on the port configured in .env.
#
# Always kills any existing instance first, so re-running this is always safe
# and never leaves stray/duplicate processes behind. `php spark serve` spawns
# a `php -S ...` child process — killing only the child isn't enough, since
# the parent `spark serve` process just respawns a new child on the next free
# port. Both must be killed together.
set -e

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$PROJECT_DIR"

BASE_URL=$(grep -m1 '^app\.baseURL' .env | sed -E "s/.*'(.*)'.*/\1/")
PORT=$(echo "$BASE_URL" | sed -E 's#.*:([0-9]+)/?$#\1#')
HOST=127.0.0.1

if [ -z "$PORT" ] || [ "$PORT" = "$BASE_URL" ]; then
  echo "Could not parse a port from .env's app.baseURL ('$BASE_URL') — defaulting to 8092"
  PORT=8092
fi

echo "Stopping any existing mdgoatco dev server..."
pkill -f "php spark serve" 2>/dev/null && sleep 1 || true
pkill -f "php -S.*${PROJECT_DIR}/public" 2>/dev/null && sleep 1 || true

if lsof -i ":${PORT}" -sTCP:LISTEN >/dev/null 2>&1; then
  echo "ERROR: port ${PORT} is still in use by something else (not an mdgoatco process)."
  lsof -i ":${PORT}" -sTCP:LISTEN
  exit 1
fi

echo "Starting mdgoatco at http://${HOST}:${PORT}"
exec php spark serve --host "$HOST" --port "$PORT"
