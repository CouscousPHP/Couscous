# Getting started

## Installation

If you have already [set up a global install of Composer](http://akrabat.com/php/global-installation-of-php-tools-with-composer/) just run:

```
composer global require mnapoli/couscous
```

Alternatively, you can download [couscous.phar](http://mnapoli.fr/Couscous/couscous.phar):

```bash
curl -OS http://mnapoli.fr/Couscous/couscous.phar
```

If you want to run `couscous` instead of `php couscous.phar`, move it to `/usr/local/bin`:

```bash
chmod +x couscous.phar
sudo mv couscous.phar /usr/local/bin/couscous
```

## Preview

Let's not waste time and preview the website immediately:

```bash
couscous preview
```

If all goes well, Couscous should process all the Markdown files inside your current directory
and start up a webserver. You can now visit [http://localhost:8000/](http://localhost:8000/)
to preview the result!
