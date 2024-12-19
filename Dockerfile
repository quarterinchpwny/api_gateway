# Stage 1: Build
FROM php:8.2-cli as build

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory and copy source code
WORKDIR /app
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Stage 2: Production
FROM php-base:latest

# Copy built application from Stage 1
COPY --from=build /app /var/www/html
