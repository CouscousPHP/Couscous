# Contributing

First of all, **thank you** for contributing!

Here are a few rules to follow in order to ease code reviews and merging:

- follow [PSR-1](https://www.php-fig.org/psr/psr-1/) and [PSR-2](https://www.php-fig.org/psr/psr-2/)
- run the test suite
- write (or update) unit tests when applicable
- write documentation for new features
- use [commit messages that make sense](https://tbaggery.com/2008/04/19/a-note-about-git-commit-messages.html)

One may ask you to [squash your commits](http://gitready.com/advanced/2009/02/10/squashing-commits-with-rebase.html) too. This is used to "clean" your pull request before merging it (we don't want commits such as `fix tests`, `fix 2`, `fix 3`, etc.).

When creating your pull request on GitHub, please write a description which gives the context and/or explains why you are creating it.

## Requirements

To be able to preview Couscous own website on your machine, you will need the following tools installed:

- Bower:

    ```
    $ npm install -g bower
    ```

- Less compiler:

    ```
    $ npm install -g less less-plugin-clean-css
    ```

- Phar generation enabled in `php.ini`:

    ```
    phar.readonly = Off
    ```

Then you can run `bin/couscous preview`.

## Building the phar

To generate the phar yourself, you need to allow Phar generation in your `php.ini` ([`phar.readonly = Off`](https://www.php.net/manual/en/phar.configuration.php#ini.phar.readonly)).

You also need to be able to use the `sha1sum` cli tool.

The procedure is then quite simple, check out the repository and:

```
$ composer update
$ bin/compile
```

The phar is generated as `bin/couscous.phar`.

## Releasing a new version

Instructions for maintainers:

```
$ composer update
$ bin/compile
$ cp bin/couscous.* website/
$ bin/couscous deploy
```
