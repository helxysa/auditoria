
# Usa imagem oficial do PHP 8.4 com FPM
FROM php:8.4-fpm

# Define diretório de trabalho
WORKDIR /var/www

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    git \
    unzip \
    zip \
    vim \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    ca-certificates

# Instala o Node.js 18.x diretamente da NodeSource
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Instala extensões do PHP para Laravel
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia o código da aplicação para o container
COPY . /var/www

# ---- MUDANÇA IMPORTANTE ----
# Executa a instalação e o build como ROOT, que tem acesso a tudo.
RUN composer install --no-interaction --prefer-dist --optimize-autoloader \
    && npm install \
    && npm run build

# Cria usuário não-root para rodar a app
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

# Ajusta permissões de TODOS os ficheiros (incluindo node_modules e build) para o novo usuário
RUN chown -R www:www /var/www
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Agora sim, troca para o usuário seguro para executar a aplicação
USER www

# Expõe porta para PHP-FPM
EXPOSE 9000

# Comando padrão
CMD ["php-fpm"]
