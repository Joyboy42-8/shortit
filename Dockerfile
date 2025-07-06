FROM php:8.2-apache

# Installe les dépendances nécessaires pour compiler les extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libjpeg-dev \
    libxml2-dev \
    libzip-dev \
    zip unzip \
    default-mysql-client \
    default-libmysqlclient-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql

# Active mod_rewrite d'Apache
RUN a2enmod rewrite

# Copie les fichiers du projet
COPY . /var/www/html/

# Permissions sur les fichiers
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
