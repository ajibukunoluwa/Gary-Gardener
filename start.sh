#!/bin/bash

composer install
npm install

php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve --port=3000
