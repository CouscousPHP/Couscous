#!/bin/sh

set -e

git pull
composer update
vendor/bin/phpunit
bin/compile
mv bin/couscous.phar website/
couscous deploy
