#!/bin/bash

# Install dependencies
# composer install --no-dev --optimize-autoloader

# Clear all caches
# php artisan cache:clear
# php artisan config:clear
# php artisan route:clear
# php artisan view:clear

# Publish and build Filament assets
# php artisan vendor:publish --tag=filament-assets --force
# php artisan filament:assets
# php artisan optimize

# Build frontend assets
npm install
npm run build

# Cache configurations after assets are built
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache
