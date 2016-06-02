#!/bin/sh

set -e

# Update
git pull

# Run tests
composer update -o
vendor/bin/phpunit

# Clear dev dependencies for a lighter phar
composer install -o --no-dev

# Generate the phar
bin/compile

# Generate the website
mv bin/couscous.phar website/
cp bin/couscous.version website/
couscous deploy

# Restore dev dependencies
composer install
