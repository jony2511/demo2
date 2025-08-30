# Use official PHP image
FROM php:8.2-cli

# Set working directory
WORKDIR /app

# Copy project files into the container
COPY . /app

# Install PHP extensions (optional, add more if needed)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Expose Render’s required port
EXPOSE 10000

# Run PHP built-in server on Render’s port
CMD ["php", "-S", "0.0.0.0:10000", "-t", "."]
