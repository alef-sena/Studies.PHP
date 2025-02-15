# Usar uma imagem base do PHP com Apache
FROM php:8.2-apache

# Instalar extensões do PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Habilitar o módulo do Apache para reescrita de URLs (opcional, útil para rotas amigáveis)
RUN a2enmod rewrite

# Copiar os arquivos do projeto para o container
COPY . /var/www/html/

# Definir permissões para o diretório de trabalho
RUN chown -R www-data:www-data /var/www/html

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Expor a porta 80 (HTTP)
EXPOSE 80