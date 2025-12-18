#!/bin/sh

# export SERVICE_APP_INIT="php artisan serve --host=0.0.0.0 --port=80"
export SERVICE_APP_INIT="php artisan octane:start --server=frankenphp --host=0.0.0.0 --caddyfile=/app/Caddyfile --watch"
# export SERVICE_APP_INIT="php artisan octane:start --server=roadrunner --host=0.0.0.0 --port=80 --rpc-port=6001 --watch"
# export SERVICE_APP_INIT="php artisan octane:start --server=swoole --host=0.0.0.0 --port=80 --watch"

docker compose up --build --force-recreate --remove-orphans