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

```
wget http://mnapoli.fr/Couscous/couscous.phar
chmod +x couscous.phar
sudo mv couscous.phar /usr/local/bin/couscous
```


### Configuration

You can define options in a `couscous.yml` file at the root of your repository.

Simple example:

```yaml
baseUrl: http://mnapoli.github.io/Couscous
```

That configuration file is optional. See the [complete reference](docs/configuration.md) for more information.


### Website template

Couscous will take every `*.md` file it finds in your repository and convert it to `.html` files, keeping the same directory structure
(`README.md` files a renamed to `index.html`).

In order to have those HTML files look like real web pages, you need to write a *template* for these pages.
Templates are written with Twig, and are extremely easy to write.

The template files should be in a `website/` directory at the root of your repository
(this path is customizable).

Here is the default directory layout you should create:

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

```shell
$ couscous preview
```

This command will generate the website (in a temp directory) and starts a
webserver so that you can preview the website at [http://localhost:8000](http://localhost:8000).
If files are changed, the website will be regenerated.

This command will not modify your repository.


### Deployment

```shell
$ couscous deploy
```

This command will generate the website (in a temp directory) and publish it in the `gh-pages` branch of your git repository.
This will remove everything that exists in the `gh-pages` branch, commit in your name, and **push** to GitHub.

**The `gh-pages` branch must already exist.** You can use the official
[Automatic Page Generator](https://help.github.com/articles/creating-pages-with-the-automatic-generator#the-automatic-page-generator)
to create it (or you can create it manually).

This command will not change anything in your current branch (e.g. master branch).
It will only affect the `gh-pages` branch.


## Read more

* [Documentation](docs/)
* [Building the phar](docs/contributing.md)
