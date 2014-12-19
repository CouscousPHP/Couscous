# Automatic deployment on Travis CI

Tired of deploying manually with `couscous deploy`? You can set up Travis so that it will deploy automatically for you **on each push to `master`**.

- generate and encrypt a **personal access token**
    - generate the token from your GitHub account: *Settings > Applications > Personal Access Token* (giving it public_repo permission should be enough if the repository is public)
    - install [Travis command line tool](http://blog.travis-ci.com/2013-01-14-new-client/): `$gem install travis`
    - `$travis login` in the repository directory
    - encrypt the token for storing it in `.travis.yml`: `travis encrypt GH_TOKEN=YOUR_TOKEN_HERE --add` (the --add flag will automatically write the encrypted string to your .travis.yml file)
    - your `.travis.yml` should now contain a new `secure: ...` line: all good

- install Couscous as a dependency of your documentation:

    ```
    $ composer require couscous/couscous
    ```

- update your `.travis.yml` config file like so:

    - if you have tests:

    ```
    ...

    after_success:
        - ./vendor/bin/couscous travis-auto-deploy --php-version=5.4 # choose php version accordingly to your travis configuration (default: 5.4)
    ```

    - if you don't have tests:

    ```
    ...

    script:
        - ./vendor/bin/couscous travis-auto-deploy --php-version=5.4 # choose php version accordingly to your travis configuration (default: 5.4)
    ```