# Utilise l'image officielle PHP avec Apache
FROM php:8.2-apache

# Installe les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libjpeg-dev \
    libxml2-dev \
    libzip-dev \
    zip unzip \
    libpq-dev \
    libmysqlclient-dev

# Installe les extensions PHP (PDO MySQL ici)
RUN docker-php-ext-install pdo pdo_mysql

# Active mod_rewrite d'Apache
RUN a2enmod rewrite

# Copie tous les fichiers du projet dans le conteneur
COPY . /var/www/html/

# Donne les bons droits sur les fichiers
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose le port
EXPOSE 80
