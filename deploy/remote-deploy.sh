#!/usr/bin/env bash

set -euo pipefail

deploy_path="${1:?Deployment path is required}"
release_name="${2:?Release name is required}"
php_fpm_service="${PHP_FPM_SERVICE:-php8.4-fpm}"
release_dir="$deploy_path/releases/$release_name"
shared_dir="$deploy_path/shared"
shared_database_dir="$shared_dir/database"
shared_database_path="$shared_database_dir/database.sqlite"
current_link="$deploy_path/current"
maintenance_enabled=false

if [[ ! "$release_name" =~ ^[A-Za-z0-9._-]+$ ]]; then
  printf 'Invalid release name: %s\n' "$release_name" >&2
  exit 1
fi

if [[ ! -d "$release_dir" || -L "$release_dir" ]]; then
  printf 'Release directory is missing or invalid: %s\n' "$release_dir" >&2
  exit 1
fi

for required_path in artisan vendor/autoload.php public/build/manifest.json bootstrap/cache database/migrations; do
  if [[ ! -e "$release_dir/$required_path" ]]; then
    printf 'Required release path is missing: %s\n' "$release_dir/$required_path" >&2
    exit 1
  fi
done

recover_from_maintenance() {
  if [[ "$maintenance_enabled" == true ]]; then
    php artisan up || true
  fi
}

trap recover_from_maintenance EXIT

mkdir -p "$shared_dir/storage/app/public" \
  "$shared_dir/storage/framework/cache/data" \
  "$shared_dir/storage/framework/sessions" \
  "$shared_dir/storage/framework/views" \
  "$shared_dir/storage/logs"

if [[ ! -f "$shared_dir/.env" ]]; then
  printf 'Production environment file is missing: %s/.env\n' "$shared_dir" >&2
  exit 1
fi

for shared_path in storage .env; do
  if [[ -e "$release_dir/$shared_path" && ! -L "$release_dir/$shared_path" ]]; then
    printf 'Release path must be a symlink before replacement: %s\n' "$release_dir/$shared_path" >&2
    exit 1
  fi
done

rm -f "$release_dir/storage" "$release_dir/.env"
ln -s "$shared_dir/storage" "$release_dir/storage"
ln -s "$shared_dir/.env" "$release_dir/.env"

db_connection="$(sed -n 's/^DB_CONNECTION=//p' "$shared_dir/.env" | head -n 1 | tr -d '\r\"' | xargs)"
db_connection="${db_connection:-sqlite}"
case "$db_connection" in
  sqlite)
    if ! php -m | grep -iq '^pdo_sqlite$'; then
      printf 'SQLite is configured, but the PHP CLI pdo_sqlite driver is missing. Install the PHP SQLite extension or configure another production database.\n' >&2
      exit 1
    fi

    mkdir -p "$shared_database_dir"
    if [[ -L "$shared_database_path" || ( -e "$shared_database_path" && ! -f "$shared_database_path" ) ]]; then
      printf 'Shared SQLite database path is not a regular file: %s\n' "$shared_database_path" >&2
      exit 1
    fi
    touch "$shared_database_path"

    release_database_path="$release_dir/database/database.sqlite"
    if [[ -e "$release_database_path" && ! -L "$release_database_path" ]]; then
      printf 'Release SQLite database path must be a symlink before replacement: %s\n' "$release_database_path" >&2
      exit 1
    fi
    rm -f -- "$release_database_path"
    ln -s "$shared_database_path" "$release_database_path"
    ;;
  mysql|mariadb)
    if ! php -m | grep -iq '^pdo_mysql$'; then
      printf 'MySQL is configured, but the PHP CLI pdo_mysql driver is missing. Install the PHP MySQL extension or configure another production database.\n' >&2
      exit 1
    fi
    ;;
  pgsql)
    if ! php -m | grep -iq '^pdo_pgsql$'; then
      printf 'PostgreSQL is configured, but the PHP CLI pdo_pgsql driver is missing. Install the PostgreSQL PHP extension or configure another production database.\n' >&2
      exit 1
    fi
    ;;
esac

cd "$release_dir"
php artisan down --retry=15
maintenance_enabled=true

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan event:cache

chmod -R 775 "$release_dir/bootstrap/cache"

rm -f "$current_link.next"
ln -s "$release_dir" "$current_link.next"
mv -Tf "$current_link.next" "$current_link"

php artisan up
maintenance_enabled=false
php artisan queue:restart
sudo -n systemctl reload "$php_fpm_service"

find "$deploy_path/releases" -mindepth 1 -maxdepth 1 -type d -printf '%T@ %p\n' \
  | sort -nr \
  | tail -n +6 \
  | cut -d' ' -f2- \
  | xargs -r rm -rf

printf 'Deploy selesai. Release aktif: %s\n' "$release_name"
