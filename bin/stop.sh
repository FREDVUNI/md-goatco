#!/bin/bash
# Stop the mdgoatco dev server (parent `spark serve` process and its `php -S` child).
PROJECT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

killed=0
if pkill -f "php spark serve" 2>/dev/null; then killed=1; fi
if pkill -f "php -S.*${PROJECT_DIR}/public" 2>/dev/null; then killed=1; fi

if [ "$killed" = "1" ]; then
  echo "Stopped."
else
  echo "Nothing was running."
fi
