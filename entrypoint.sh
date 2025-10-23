#!/bin/bash

# Ensure the script stops on the first error
set -e

# Generate application key
php artisan key:generate

php artisan migrate

# Install npm dependencies and build assets
#npm install
#npm run dev &

# Start the Laravel server
php artisan serve --host=0.0.0.0 --port=8000
