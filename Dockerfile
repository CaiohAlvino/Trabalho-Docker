FROM php:7.4-fpm

# Instalar extensões e dependências do PHP
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

# Instalar extensões do PHP necessárias
RUN docker-php-ext-install pdo_mysql zip

# Limpar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos da aplicação
COPY ./src /var/www/html

# Ajustar permissões
RUN chown -R www-data:www-data /var/www/html

# Modificar o arquivo de configuração do PHP-FPM para usar a porta 9001
RUN sed -i 's/listen = 9000/listen = 9001/g' /usr/local/etc/php-fpm.d/www.conf

# Expor a nova porta
EXPOSE 9001
