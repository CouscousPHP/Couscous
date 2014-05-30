---
template: home
---

Couscous generates a website (for [Github pages](http://pages.github.com/)) from your markdown documentation.


## Introduction

[Github pages](http://pages.github.com/) are good, but what do you do:

* if you want to write your documentation in **Markdown** and not in HTML?
* if you want your documentation to be transformed into a **website**?
* if you want to **keep the documentation with your code**, versioned in the repository?

Couscous is here to help you. **Your documentation is written in Markdown,
versioned in your repository with your source, compiled to HTML and published to *Github pages*.**


## How does it work

1. you write your README and documentation in `.md` files (Markdown) on your repository
2. you generate you website with *Couscous*
  - it turns `.md` files into HTML files
  - HTML files are committed to the `gh-pages` branch of your repository, and thus are published as a website by Github
3. profit!


## Getting started

### Installation

You can [download it manually](http://mnapoli.fr/Couscous/couscous.phar) or install it through CLI:

```bash
# Download couscous.phar
curl -OS http://mnapoli.fr/Couscous/couscous.phar

# Move it to /usr/local/bin
chmod +x couscous.phar
sudo mv couscous.phar /usr/local/bin/couscous
```

If you don't want to install it globally, you can also just download the phar in the current directory
and later run it with `php couscous.phar` instead of just `couscous`.


### Init

You have two options to start using Couscous:

- automatically init the project with default files
- write the files yourself

To init your project automatically, just run:

```bash
couscous init
```

The next sections explain how to create the files manually (and customize them).
You can read them now or jump to the "Preview" section and read them later.


### Configuration

You can define options in a `couscous.yml` file at the root of your repository.

Simple example:

```yaml
template:
    baseUrl: http://mnapoli.github.io/Couscous
```

That configuration file is optional. See the [complete reference](docs/configuration.md) for more information.


### Website template

Couscous will take every `*.md` file it finds in your repository and convert it to `.html` files, keeping the same directory structure
(`README.md` files a renamed to `index.html`).

In order to have those HTML files look like real web pages, you need to write a *template* for these pages.
Templates are written with Twig, and are extremely easy to write.

Templates can be:

- in your repository (the default directory is `website/`)
- in a remote repository (which allow to reuse templates)

As a quickstart, you can create a template locally. Here is the default directory layout you should create:

```
your-repository/
    couscous.yml
    website/
        public/
        page.twig
```

Basic example (`website/page.twig`):

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>My project!</title>
    </head>
    <body>
        {{ content|raw }}
    </body>
</html>
```

Variables available in the template are:

- `content`: Markdown turned to HTML. Make sur to echo it with `{{ content|raw }}` else the HTML will be escaped.
- `baseUrl`: Base URL, if defined in `couscous.yml`. When previewing with `couscous preview`, the base URL will always be `http://localhost:8000`. Useful for writing links.

You can write different templates (for example to make the home page different).
For this, read the [template documentation](docs/templates.md).

If your template has assets (CSS, JS, images, …), put them in `website/public`. The content of this directory will be
copied in the generated website.


### Preview

```bash
$ couscous preview
```

This command will generate the website (in a temp directory) and starts a
webserver so that you can preview the website at [http://localhost:8000](http://localhost:8000).
If files are changed, the website will be regenerated.

This command will not modify your repository.


### Deployment

```bash
$ couscous deploy
```

This command will generate the website (in a temp directory) and publish it in the `gh-pages` branch of your git repository.
This will remove everything that exists in the `gh-pages` branch, commit in your name, and **push** to GitHub.

**The `gh-pages` branch must already exist.** You can use the official
[Automatic Page Generator](https://help.github.com/articles/creating-pages-with-the-automatic-generator#the-automatic-page-generator)
to create it or you can create it manually:

```bash
git branch gh-pages
git push -u origin gh-pages
```

The `deploy` command will not change anything in your current branch (e.g. master branch).
It will only affect the `gh-pages` branch.


## Built using Couscous

Here are examples of website built using Couscous:

- [Couscous](http://mnapoli.fr/Couscous/) itself (of course)
- [PHP-DI](http://php-di.org)
- [MyCLabs\ACL](http://myclabs.github.io/ACL/)

Add your own to the list with a pull request.


## Read more

* [Documentation](docs/)
* [Building the phar](docs/contributing.md)
