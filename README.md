---
layout: home
---

Couscous generates a [GitHub pages](http://pages.github.com/) website from your markdown documentation.

[![Build Status](https://travis-ci.org/CouscousPHP/Couscous.svg?branch=master)](https://travis-ci.org/CouscousPHP/Couscous)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/CouscousPHP/Couscous/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/CouscousPHP/Couscous/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/CouscousPHP/Couscous/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/CouscousPHP/Couscous/?branch=master)

[![Gitter](https://badges.gitter.im/Join Chat.svg)](https://gitter.im/CouscousPHP/Couscous)

**Everything is documented on [couscous.io](http://couscous.io/).**

What follows is the documentation for contributors.

## How Couscous works?

Couscous was designed to be as simple as possible. By embracing simplicity, it becomes extremely simple to extend.

### Website generation

The website generation is composed of a list of **steps** to process the `Repository` model object:

```php
interface Step
{
    /**
     * Process the given repository.
     *
     * @param Repository      $repository
     * @param OutputInterface $output     Output for the user.
     */
    public function __invoke(Repository $repository, OutputInterface $output);
}
```

Steps are very granular, thus extremely easy to write and test. For example:

- `LoadConfig`: loads the `couscous.yml` config file
- `RunBowerInstall`
- `LoadMarkdownFiles`: load the content of all the `*.md` files in memory
- `RenderMarkdown`: render the markdown content
- `WriteFiles`: write the in-memory processed files to the target directory
- …

For example, here is a step that would preprocess Markdown files to put the word "Couscous" in bold:

```php
class PutCouscousInBold implements \Couscous\Step
{
    public function __invoke(Repository $repository, OutputInterface $output)
    {
        /** @var MarkdownFile[] $markdownFiles */
        $markdownFiles = $repository->findFilesByType('Couscous\Model\MarkdownFile');

        foreach ($markdownFiles as $file) {
            $file->content = str_replace('Couscous', '**Couscous**', $file->content);
        }
    }
}
```

Couscous uses [PHP-DI](http://php-di.org/) for wiring everything together with dependency injection.

The full list of steps is configured in [`src/config.php`](src/config.php).

### Website deployment

Couscous deploys by cloning (in a temp directory) the current repository, checking out the `gh-pages` branch, generating the website inside it, committing and pushing.

In the future, Couscous will support several deployment strategies.

## Contributing

See the [CONTRIBUTING](contributing.md) file.

## License

Couscous is released under the MIT License.
