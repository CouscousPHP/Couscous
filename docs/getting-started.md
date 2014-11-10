# Getting started

## Requirements

Couscous requires PHP 5.4 or above.

## Installation

If you have already [set up a global install of Composer](http://akrabat.com/php/global-installation-of-php-tools-with-composer/) just run:

```
$ composer global require couscous/couscous
```

You can also add Couscous as a dependency for a project with `composer require couscous/couscous`.

Alternatively, you can download [couscous.phar](http://couscous.io/couscous.phar):

```bash
$ curl -OS http://couscous.io/couscous.phar
```

If you want to run `couscous` instead of `php couscous.phar`, move it to `/usr/local/bin`:

```bash
$ chmod +x couscous.phar
$ sudo mv couscous.phar /usr/local/bin/couscous
```

## Preview

Let's not waste time and run:

```bash
$ couscous preview
```

Couscous will take every `*.md` file it finds in your repository and convert it to HTML files, keeping the same directory structure (`README.md` files will be renamed to `index.html`). You can then visit [http://localhost:8000/](http://localhost:8000/) to preview the result!

Be assured that this command will not modify your repository.

## Deploy

Happy with the result? Here is how to deploy:

```bash
$ couscous deploy
```

Couscous will generate the website (in a temp directory) and publish it in the `gh-pages` branch of your git repository. This will remove everything that exists in the `gh-pages` branch, commit in your name, and **push** to GitHub.

The website is now online: [http://your-username.github.io/your-project/](http://your-username.github.io/your-project/).

The `deploy` command will not change anything in your current branch (e.g. master branch). It will only affect the `gh-pages` branch.

## Customizing the template

Couscous provides a default template, but you can of course come up with your own. Writing templates is really simple, and it is all explained in the [templates documentation](templates.md).
