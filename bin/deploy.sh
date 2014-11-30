#!/bin/bash
if [ ! "$TRAVIS_BRANCH" = "master" ]
then
    echo "[NOT DEPLOYED] Deploying Couscous only for master branch"
    exit 1
fi

if [ ! "$TRAVIS_PULL_REQUEST" = false ]
then
    echo "[NOT DEPLOYED] Not deploying Couscous for pull requests"
    exit 1
fi

mkdir out
echo "Deploying Couscous..."
(
git config user.name ${GIT_NAME}
git config user.email ${GIT_EMAIL}
bin/couscous deploy --repository="https://${GH_TOKEN}@${GH_REF}"
)
