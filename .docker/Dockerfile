FROM circleci/php:7.3-node-browsers

RUN sudo apt-get update && \
    sudo apt-get install -y libmcrypt-dev libsqlite3-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev zlib1g-dev && \
    sudo docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    sudo docker-php-ext-install -j$(nproc) iconv gd zip pdo_sqlite bcmath && \
    sudo pecl install grpc && \
    sudo docker-php-ext-enable grpc && \
    sudo rm /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini