---
current_menu: getting-started
---
# Getting started

## Requirements

Couscous requires PHP 5.4 or above on your machine.

## Installation

### Composer

If you have already [set up a global install of Composer](http://akrabat.com/php/global-installation-of-php-tools-with-composer/) just run:

```
$ composer global require couscous/couscous
```

You can also add Couscous as a dependency for a project with `composer require couscous/couscous`.

Be aware that in order for Couscous to be awesome it will install a good amount of other dependencies.
If you rather have it self-contained, use the **Phar** method just below.

### Phar

Alternatively, you can download [couscous.phar](http://couscous.io/couscous.phar):

```bash
$ curl -OS http://couscous.io/couscous.phar
```

If you want to run `couscous` instead of `php couscous.phar`, move it to `/usr/local/bin`:

```bash
$ chmod +x couscous.phar
$ sudo mv couscous.phar /usr/local/bin/couscous
```

Please note that you need to have the `phar` extension installed to use this method. It should be installed by default on most OSes.

## Preview

Let's not waste time and run:

```bash
$ couscous preview
```

Couscous will take every `*.md` file it finds in your current directory and convert it to HTML files, keeping the same directory structure (`README.md` files will be renamed to `index.html`). You can then visit [http://localhost:8000/](http://localhost:8000/) to preview the result!

Be assured that this command will not modify your repository.

Liking what you see? The default template comes with a header and an optional left menu (which is hidden if it is not configured). You can configure all that [by creating a `couscous.yml`](configuration.md) and checking out all the options [on the template's homepage](https://github.com/CouscousPHP/Template-Light).

#### Specify an Address

While the default `couscous preview` address will work on most installations, there may be times where you need to specify the preview IP address (for example if using VM or container tools like Vagrant or Docker). To specify an address, in this example `0.0.0.0:8000`, use:

```
couscous preview 0.0.0.0:8000
```


## Deploy

Happy with the result? Here is how to deploy:

```bash
$ couscous deploy
```

Couscous will generate the website (in a temp directory) and publish it in the `gh-pages` branch of your git repository. This will remove everything that exists in the `gh-pages` branch, commit in your name, and **push** to GitHub.

The website is now online: [http://your-username.github.io/your-project/](http://your-username.github.io/your-project/).

The `deploy` command will not change anything in your current branch (e.g. master branch). It will only affect the `gh-pages` branch.

#### Deploying to a branch other than `gh-pages`

If you wish to have Couscous generate and deploy pages to a branch other than `gh-pages`, use the `--branch` option. For example:

```
couscous deploy --branch master
```

## Customizing the template

Couscous provides [a default template](https://github.com/CouscousPHP/Template-Light) (and a [few others to choose from](http://couscous.io/templates.html)), but you can of course come up with your own. Writing templates is really simple, and it is all explained in the [templates documentation](templates.md).
