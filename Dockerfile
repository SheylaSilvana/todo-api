# Dockerfile
FROM php:8.3.9-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer versão 2.7.9
RUN curl -sS https://getcomposer.org/installer | php -- --version=2.7.9 --install-dir=/usr/bin --filename=composer

# Configurar diretório de trabalho
WORKDIR /var/www

# Copiar o projeto para o container
COPY . .

# Instalar dependências do PHP com o Composer
RUN composer install

# Permissões para diretórios de storage e cache
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Expor a porta do servidor PHP
EXPOSE 8080

# Comando de inicialização
CMD ["sh", "-c", "php artisan migrate && php -S 0.0.0.0:8080 -t public"]
