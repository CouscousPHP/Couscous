#!/bin/sh

set -e

# Clear dev dependencies for a lighter phar
composer install -o --no-dev

# Generate the phar
bin/compile

# Generate the website
cp bin/couscous.phar website/
cp bin/couscous.version website/
