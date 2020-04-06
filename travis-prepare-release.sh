#!/bin/sh

set -e

# Clear dev dependencies for a lighter phar
composer install -o --no-dev

# Update version
sed -i -re "s/dev-master/`git describe --abbrev=0 --tags`/" src/Application/config.php

# Generate the phar
bin/compile

# Generate the website
cp bin/couscous.phar website/
cp bin/couscous.version website/
