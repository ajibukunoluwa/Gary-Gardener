#!/bin/bash

composer install
npm install
npm run production

php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve --port=3000
