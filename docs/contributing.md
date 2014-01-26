# Contributing

## Building the phar

To generate the phar yourself, you need to allow Phar generation in your `php.ini` ([`phar.readonly = On`](http://us1.php.net/manual/en/phar.configuration.php#ini.phar.readonly)).

The procedure is then quite simple, check out the repository and:

```
$ composer install
$ bin/compile
```

The phar is generated as `bin/couscous.phar`.
