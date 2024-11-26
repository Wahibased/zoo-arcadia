# Utiliser l'image officielle de PHP avec Apache
FROM php:8.1-apache

# Installer des extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Activer le module mod_rewrite d'Apache
RUN a2enmod rewrite

# Définir le ServerName globalement pour Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copier le code du projet dans le conteneur
COPY . /var/www/html/

# Exposer le port 80
EXPOSE 80

# Lancer Apache en mode avant-plan
CMD ["apache2-foreground"]

# Définir le ServerName globalement pour Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
