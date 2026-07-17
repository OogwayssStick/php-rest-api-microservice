FROM php:8.3-apache

RUN apt-get update && \
    apt-get install -y \
        zip \
        unzip \
        git \
        libzip-dev && \
    docker-php-ext-install mysqli pdo pdo_mysql zip && \
    a2enmod rewrite && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Proje dosyalarını container'a kopyala
COPY . /var/www/html/

# Apache configuration
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Logs klasörünü oluştur ve izinleri ayarla
RUN mkdir -p /var/www/html/logs && \
    chown -R www-data:www-data /var/www/html/logs && \
    chmod -R 775 /var/www/html/logs

WORKDIR /var/www/html

EXPOSE 80
