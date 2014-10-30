# Changelog

# 1.0.0

BC breaking changes:

- the default template is now `default.twig` (it was previously `page.twig`)

Major new features:

- [#10](https://github.com/mnapoli/Couscous/pull/10) automatically install Bower dependencies if a `bower.json` file is in `website/public/`

Minor new features:

- The preview command will show the name of the files that have changed when regenerating

Bugfixes

- [#7](https://github.com/mnapoli/Couscous/issues/7), [#12](https://github.com/mnapoli/Couscous/issues/12) The preview command now detects any file change (to regenerate the website)
