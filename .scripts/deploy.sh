#!/bin/bash

#Entrar no modo Manutenção
(php artisan down) || true

#reset
git reset HEAD --hard
#Atualizando Branch
git pull origin main

#instalar Composer
composer install --no-interaction --prefer-dist --optimize-autoloader


#optimize
php artisan optimize

#compilar os assets
#nvm

export NVM_DIR="~/.nvm"
source ~/.nvm/nvm.sh

npm install --yes

npm run build


#migrations
php artisan migrate --force


#sair do modo manutenção
php artisan up
