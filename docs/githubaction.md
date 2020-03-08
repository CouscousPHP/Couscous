---
current_menu: action
---
# Automatic deployment using GithubAction

You want to use add Couscous in your workflow Github.

## Create Workflow in `.github/workflows/***.yml`
```yml
name: Couscous GithubPage

on:
  push:
    branches:
      - master

jobs:
  deploy:
    runs-on: ubuntu-18.04
    steps:
      - uses: actions/checkout@v1

      - uses: `BilelJegham/action-couscous@v0`
      - name: Deploy
        uses: peaceiris/actions-gh-pages@v3
        with:
          github_token: ${{ secrets.GITHUB_TOKEN }}
          publish_dir: ./.couscous/generated
```