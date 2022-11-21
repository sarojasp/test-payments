#!/bin/bash
echo "================== Install =================="
echo "Develop with Test Environment"
echo "================== Installing App dependencies =================="
# rm -rf vendor && composer install --no-interaction

echo "================== Installing App Database =================="
php artisan migrate:fresh --seed