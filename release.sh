#!/bin/sh

git pull
bin/compile
mv bin/couscous.phar website/
couscous deploy
