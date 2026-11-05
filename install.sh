#!/usr/bin/env bash
set -euo pipefail
# Create Laravel as a sibling of this source folder to avoid copying into itself.
SOURCE_DIR="$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")" && pwd)"
NAME="${1:-dealbd}"
if [[ "$NAME" == /* ]]; then TARGET="$NAME"; else TARGET="$(dirname "$SOURCE_DIR")/$NAME"; fi
if [[ -e "$TARGET" ]]; then
  echo "Target path exists: $TARGET" >&2
  exit 1
fi
composer create-project laravel/laravel "$TARGET" '^13.0'
cp -R "$SOURCE_DIR"/. "$TARGET"/
cd "$TARGET"
php artisan key:generate
printf '\nInstalled files in %s. Set your DB credentials in .env, then run:\nphp artisan migrate --seed\nphp artisan serve\n' "$(pwd)"
