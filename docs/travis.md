---
current_menu: travis
---
# Automatic deployment using Travis CI

Tired of deploying manually with `couscous deploy`? You can set up Travis so that it will deploy automatically for you **on each push to `master`**.

To set this up, please follow all the steps below.

## 1. Create a **GitHub token**

In order to push to your repository from Travis, you need to authorize Travis. You do so by creating a **GitHub token** and then **encrypting** it to store it in `.travis.yml`.

- from your GitHub account, go in [*Settings > Personal access tokens*](https://github.com/settings/tokens)
- create a new **Personal Access Token** and give it the `public_repo` permission (if the repository is public)
- install the [Travis command line tool](https://blog.travis-ci.com/2013-01-14-new-client/)

    `gem install travis`

- run `travis login` in the repository directory
- encrypt the token:

    `travis encrypt GH_TOKEN=YOUR_TOKEN_HERE --add`

    The `--add` flag will automatically write the encrypted string to your .travis.yml file.

- your `.travis.yml` should now contain a new `secure` line looking like this:

    ```yaml
    env:
      global:
        - secure: aeuwi73FIpzkIuk0XkbxiYb5M+HzTIKJy/eiBa5gWhSWq6FlI1fysDGvDcExnKyNH0z9sud0sNPBbi5O5z/uiqupoxoBLuFHfLG3NnLrvjQ2SAmrsIBWtsU737Vo5klbfJp2oJ0hrQCIlczwkfK5j+HbQmGUoS5w81pr3kPxnst=
    ```

## 2. Install Couscous

Couscous must be installed to be used in Travis. If you have been using the Phar, you should install Couscous using Composer instead:

```
$ composer require couscous/couscous
```

This will update your `composer.json`.

## 3. Update `.travis.yml`

Here is an example of what your `.travis.yml` might look like. This assumes **you use Travis to run PHPUnit tests**. If not, look at the next section.

```yml
language: php
php:
  - 7.3
env:
  global:
    - GIT_NAME: "'Couscous auto deploy'"
    - GIT_EMAIL: couscous@couscous.io
    - GH_REF: github.com/CouscousPHP/Couscous
    - secure: ...
before_script:
  - composer install --no-progress
script:
  - phpunit
after_success:
  - vendor/bin/couscous travis-auto-deploy
```

As you can see above, you need to set up:

- `GIT_NAME` and `GIT_EMAIL`
- `GH_REF` is the GitHUb repository URL
- `composer install` is necessary to install Couscous
- `vendor/bin/couscous travis-auto-deploy`

### Without PHPUnit

If you use Travis only to update the website, then use `script:` instead of `after_success`:

```yml
language: php
php:
  - 5.4
env:
  ...
before_script:
  - composer install --no-progress
script:
  - vendor/bin/couscous travis-auto-deploy
```

### Options

If you run your tests against multiple PHP versions, you want to deploy the website only once (i.e. not for all PHP versions). To do this, `travis-auto-deploy` will deploy only when the PHP version is **5.4**.

You can customize this using the `--php-version` option:

```yaml
language: php
php:
  - 5.5
...
after_success:
  - vendor/bin/couscous travis-auto-deploy --php-version=5.5
```

Be also aware that `travis-auto-deploy` will only deploy the website for pushes on the `master` branch.
