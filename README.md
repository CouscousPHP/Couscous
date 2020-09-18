---
layout: home
---

Couscous generates a [GitHub pages](https://pages.github.com/) website from your markdown documentation.

[![Build Status](https://travis-ci.org/CouscousPHP/Couscous.svg?branch=master)](https://travis-ci.org/CouscousPHP/Couscous)
[![Average time to resolve an issue](https://isitmaintained.com/badge/resolution/CouscousPHP/Couscous.svg)](https://isitmaintained.com/project/CouscousPHP/Couscous "Average time to resolve an issue")
[![Percentage of issues still open](https://isitmaintained.com/badge/open/CouscousPHP/Couscous.svg)](https://isitmaintained.com/project/CouscousPHP/Couscous "Percentage of issues still open")

**Everything is documented on [couscous.io](https://couscous.io/).**

What follows is the documentation for contributors.

## How Couscous works?

Couscous was designed to be as simple as possible. By embracing simplicity, it becomes extremely simple to extend.

### Website generation

The website generation is composed of a list of **steps** to process the `Project` model object:

```php
interface Step
{
    /**
     * Process the given project.
     *
     * @param Project $project
     */
    public function __invoke(Project $project);
}
```

Steps are very granular, thus extremely easy to write and test. For example:

- `LoadConfig`: load the `couscous.yml` config file
- `InstallDependencies`: install the dependencies (using yarn, npm or bower)
- `LoadMarkdownFiles`: load the content of all the `*.md` files in memory
- `RenderMarkdown`: render the markdown content
- `WriteFiles`: write the in-memory processed files to the target directory
- …

For example, here is a step that would preprocess Markdown files to put the word "Couscous" in bold:

```php
class PutCouscousInBold implements \Couscous\Step
{
    public function __invoke(Project $project)
    {
        /** @var MarkdownFile[] $markdownFiles */
        $markdownFiles = $project->findFilesByType('Couscous\Model\MarkdownFile');

        foreach ($markdownFiles as $file) {
            $file->content = str_replace('Couscous', '**Couscous**', $file->content);
        }
    }
}
```

Couscous uses [PHP-DI](https://php-di.org/) for wiring everything together with dependency injection.

The full list of steps is configured in [`src/Application/config.php`](src/Application/config.php).

### Website deployment

Couscous deploys by cloning (in a temp directory) the current repository, checking out the `gh-pages` branch, generating the website inside it, committing and pushing.

In the future, Couscous will support several deployment strategies.

## Contributing

See the [CONTRIBUTING](CONTRIBUTING.md) file.

## License

Couscous is released under the MIT License.
