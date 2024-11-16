FROM php:8.3-apache

WORKDIR /var/www/html

RUN apt-get update \
  && apt-get install -y \
	libjpeg62-turbo-dev \
	libpng-dev \
  build-essential \
  curl \
  jpegoptim optipng pngquant gifsicle \
  libfreetype6-dev \
  libjpeg-dev \
  libmagickwand-dev \
  libonig-dev \
  libpq-dev \
  libssl-dev \
  libwebp-dev \
  libxml2-dev \
  libzip-dev git \
  locales \
  unzip \
  zip \
  zlib1g-dev \
  vim \
  && apt-get clean

RUN pecl install imagick \
  && docker-php-ext-enable imagick \
  && docker-php-ext-install mbstring gd pdo_pgsql pdo xml \
  && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg

COPY ./src /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]
