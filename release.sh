#!/bin/sh

set -e

# Update
git pull

# Run tests
composer update -o
vendor/bin/phpunit

# Clear dev dependencies for a lighter phar
composer install -o --no-dev

# Update version
sed -i -re "s/dev-master/`git describe --abbrev=0 --tags`-`git rev-parse --short HEAD`/" src/Application/config.php

# Generate the phar
bin/compile

# Generate the website
mv bin/couscous.phar website/
cp bin/couscous.version website/
couscous deploy

# Restore dev dependencies
composer install
