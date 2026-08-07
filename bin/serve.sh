#!/bin/bash
# Start (or restart) the mdgoatco dev server on the port configured in .env.
#
# Always kills any existing instance first, so re-running this is always safe
# and never leaves stray/duplicate processes behind. `php spark serve` spawns
# a `php -S ...` child process — killing only the child isn't enough, since
# the parent `spark serve` process just respawns a new child on the next free
# port. Both must be killed together.
#
# If the configured port turns out to be held by something that ISN'T our own
# (already-killed) process — another project on this machine, say — this
# auto-picks the next free port instead of just failing, and rewrites both
# .env and app/Config/App.php so baseURL stays in sync with whatever port it
# actually starts on. A mismatched baseURL is what makes the UI look
# "broken" (unstyled): every asset URL the app generates points at the old
# port, so CSS/JS 404 even though the page itself loads fine.
set -e

PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$PROJECT_DIR"

ENV_FILE="$PROJECT_DIR/.env"
CONFIG_FILE="$PROJECT_DIR/app/Config/App.php"
HOST=127.0.0.1

BASE_URL=$(grep -m1 '^app\.baseURL' "$ENV_FILE" | sed -E "s/.*'(.*)'.*/\1/")
PORT=$(echo "$BASE_URL" | sed -E 's#.*:([0-9]+)/?$#\1#')

if [ -z "$PORT" ] || [ "$PORT" = "$BASE_URL" ]; then
  echo "Could not parse a port from .env's app.baseURL ('$BASE_URL') — defaulting to 8092"
  PORT=8092
fi

echo "Stopping any existing mdgoatco dev server..."
pkill -f "php spark serve" 2>/dev/null && sleep 1 || true
pkill -f "php -S.*${PROJECT_DIR}/public" 2>/dev/null && sleep 1 || true

ORIGINAL_PORT="$PORT"
if lsof -i ":${PORT}" -sTCP:LISTEN >/dev/null 2>&1; then
  echo "Port ${PORT} is held by another process (not mdgoatco's, since we just stopped ours):"
  lsof -i ":${PORT}" -sTCP:LISTEN
  echo "Looking for a free port instead..."
  for candidate in $(seq $((PORT + 1)) $((PORT + 20))); do
    if ! lsof -i ":${candidate}" -sTCP:LISTEN >/dev/null 2>&1; then
      PORT="$candidate"
      break
    fi
  done
  if [ "$PORT" = "$ORIGINAL_PORT" ]; then
    echo "ERROR: could not find a free port in range ${ORIGINAL_PORT}-$((ORIGINAL_PORT + 20))."
    exit 1
  fi
  echo "Using port ${PORT} instead — updating .env and app/Config/App.php to match."
  NEW_BASE_URL="http://${HOST}:${PORT}/"
  sed -i.bak -E "s#^app\.baseURL = .*#app.baseURL = '${NEW_BASE_URL}'#" "$ENV_FILE" && rm -f "${ENV_FILE}.bak"
  sed -i.bak -E "s#public string \\\$baseURL = '[^']*';#public string \$baseURL = '${NEW_BASE_URL}';#" "$CONFIG_FILE" && rm -f "${CONFIG_FILE}.bak"
fi

echo "Starting mdgoatco at http://${HOST}:${PORT}"
exec php spark serve --host "$HOST" --port "$PORT"
