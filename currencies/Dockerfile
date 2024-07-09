FROM php:8.3-fpm

# Create a non-root user and group
RUN groupadd -r app && useradd -r -g app app

# Set working directory
WORKDIR /app

# Install system dependencies, update, and upgrade packages
RUN apt-get update && apt-get upgrade -y --no-install-recommends && \
    apt-get install -y --no-install-recommends \
        git \
        unzip \
        librabbitmq-dev \
        libzip-dev \
        libicu-dev \
        libmemcached-dev \
        zlib1g-dev \
        libsqlite3-dev \
        libbz2-dev \
        && pecl install memcached \
        && docker-php-ext-enable memcached \
        && rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    composer --version

# Change ownership of the working directory to the non-root user
RUN chown -R app:app /app

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    sockets \
    bcmath \
    bz2 \
    opcache

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configure Xdebug for code coverage
RUN echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Copy custom php.ini and opcache.ini files
COPY docker/php/php.ini /usr/local/etc/php/conf.d/php.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Expose the port Nginx will use (usually 9000 is for PHP-FPM, Nginx typically uses 80)
EXPOSE 9000

# Switch to the non-root user
USER app

# Set the entry point
CMD ["php-fpm"]
