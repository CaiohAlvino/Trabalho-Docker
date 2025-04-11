FROM php:7.4-fpm

# Instalar dependências do sistema e extensões PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos da aplicação
COPY ./src /var/www/html

# Instalar dependências do Composer
RUN composer install --no-dev --no-scripts --no-autoloader

# Gerar autoload do Composer
RUN composer dump-autoload --no-dev --optimize

# Ajustar permissões
RUN chown -R www-data:www-data /var/www/html

# Expor porta do PHP-FPM
EXPOSE 9000

# Comando padrão
CMD ["php-fpm"]