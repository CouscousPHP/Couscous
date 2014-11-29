#!/bin/bash
if [ ! "$TRAVIS_BRANCH" = "master" ]
then
    echo "[NOT DEPLOYED] Current branch is not master"
    exit 1
fi

if [ ! "$TRAVIS_PULL_REQUEST" = false ]
then
    echo "[NOT DEPLOYED] Not pushing documentation for pull requests"
    exit 1
fi

mkdir out
echo "Starting deploy..."
(
cd out
git init
git config user.name ${GIT_NAME}
git config user.email ${GIT_EMAIL}
cp ../website/* ./
git add .
git commit -m "Deployed to Github Pages"
git push --force "https://${GH_TOKEN}@${GH_REF}" master:gh-pages
)