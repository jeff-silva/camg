FROM dunglas/frankenphp:1-php8.4.15
WORKDIR /app

RUN apt-get update && apt-get install -y \
  git unzip libpq-dev libzip-dev libpng-dev libssl-dev libaio-dev libcurl4-openssl-dev inotify-tools \
  && rm -rf /var/lib/apt/lists/*

RUN pecl install mongodb \
  && docker-php-ext-enable mongodb \
  && pecl install swoole \
  && docker-php-ext-enable swoole

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql zip exif bcmath sockets gd pcntl

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN chown -R www-data:www-data /app

COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["sh", "-c", "$SERVICE_APP_INIT"]