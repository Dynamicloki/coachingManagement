FROM php:8.3-cli-alpine

# Install SQLite dependencies and build PDO SQLite driver
RUN apk add --no-cache sqlite sqlite-dev \
    && docker-php-ext-install pdo pdo_sqlite

WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Ensure writable permissions for SQLite database and backup files
RUN mkdir -p /var/www/html/backups \
    && chmod -R 777 /var/www/html

# Render injects the $PORT environment variable (default: 10000)
ENV PORT=10000
EXPOSE 10000

# Start PHP server listening on 0.0.0.0 and Render's dynamic port
CMD sh -c "php -S 0.0.0.0:${PORT:-10000} -t /var/www/html"
