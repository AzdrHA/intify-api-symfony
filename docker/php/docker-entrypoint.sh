#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

php -d memory_limit=-1 $(which composer)  install --prefer-dist --no-progress --no-interaction
php -d memory_limit=-1 $(which composer)  dump-autoload --classmap-authoritative --optimize
php -d memory_limit=-1 bin/console assets:install

mkdir -p var/cache var/log public/media

until bin/console doctrine:query:sql "select 1" >/dev/null 2>&1; do
    (>&2 echo "Waiting for MySQL to be ready...")
  sleep 1
done

bin/console doctrine:migrations:sync-metadata-storage
bin/console doctrine:cache:clear-metadata
bin/console doctrine:migrations:migrate --no-interaction

exec docker-php-entrypoint "$@"