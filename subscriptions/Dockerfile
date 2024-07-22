FROM php:8.3-fpm

# Set the PHP version variable
ENV PHP_VERSION 8.3

# Create a non-root user and group
RUN groupadd -r app && useradd -r -g app app

# Set working directory
WORKDIR /app

# Install system dependencies, update, and upgrade packages
RUN apt-get update && apt-get upgrade -y --no-install-recommends

RUN apt-get install -y --no-install-recommends git
RUN apt-get install -y --no-install-recommends unzip
RUN apt-get install -y --no-install-recommends librabbitmq-dev
RUN apt-get install -y --no-install-recommends libzip-dev
RUN apt-get install -y --no-install-recommends libicu-dev
RUN apt-get install -y --no-install-recommends libmemcached-dev
RUN apt-get install -y --no-install-recommends zlib1g-dev
RUN apt-get install -y --no-install-recommends libsqlite3-dev
RUN apt-get install -y --no-install-recommends libbz2-dev

RUN pecl install memcached && docker-php-ext-enable memcached
RUN pecl install grpc &&  docker-php-ext-enable grpc
RUN pecl install protobuf && docker-php-ext-enable protobuf

# Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

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
