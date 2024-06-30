# Builder stage
FROM composer:2.7 AS composer

# Runtime stage
FROM php:8.1-fpm AS runtime

# Install dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    zip \
    unzip \
    git \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    nodejs \
    npm \
 && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install -j$(nproc) gd zip mysqli pdo pdo_mysql bcmath exif

# Copy Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Create a non-root user with the same UID/GID as your host user
ARG USER_ID=1000
ARG GROUP_ID=1000
RUN groupadd -g ${GROUP_ID} appuser && \
    useradd -u ${USER_ID} -g appuser -m appuser && \
    chown -R appuser:appuser /var/www/html

USER appuser

EXPOSE 9000

CMD ["php-fpm"]