FROM node:23

# PHP telepítése
RUN apt-get update && apt-get install -y php-cli unzip git \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /application
