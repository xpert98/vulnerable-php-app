FROM php:8.2-apache

# The stock php:8.x image already bundles pdo + pdo_mysql. Install them only
# if a future base image drops them.
RUN if ! php -m | grep -qi '^pdo_mysql$'; then \
        docker-php-ext-install pdo pdo_mysql; \
    fi

# Copy application code.
COPY . /var/www/html

# Ensure the upload directory exists and is writable by the web user.
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads

# Document root already defaults to /var/www/html in the base image.

EXPOSE 80
ENV APP_ENV=docker
