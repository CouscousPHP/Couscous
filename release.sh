#!/bin/sh

set -e

git pull
composer update
vendor/bin/phpunit
bin/compile
mv bin/couscous.phar website/
cp bin/couscous.version website/
couscous deploy
