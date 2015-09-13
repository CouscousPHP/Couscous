---
current_menu: configuration
---
# Configuration with couscous.yml

You can define options in a `couscous.yml` file at the root of your repository.

That configuration file is optional.

Reference:

```yaml
template:
    # Name of the directory containing the website template (default is "website")
    directory: website
    # Or if you are using a remote template, you can set the Git URL
    url: https://github.com/CouscousPHP/Template-Light.git
    # Name of the index file (default is "README.md")
    index: index.md

# List of directories to exclude from the processing (default contains "vendor" and "website")
# Paths are relative to the repository root
exclude:
    - vendor
    - website
    - some/dir

scripts:
    # Scripts to execute before generating the website
    before:
        - cp bin/couscous.phar website/
    # Scripts to execute after generating the website
    after:
        - rm website/couscous.phar

# Any variable you put in this file is also available in the Twig layouts:
title: Hello!

# Base URL of the published website (no "/" at the end!)
# You are advised to set and use this variable to write your links in the HTML layouts
baseUrl: http://username.github.io/your-project
```

**Note:** any variable you put in `couscous.yml` is called **Metadata**. You can use these variables in templates for example. Learn more about this in the [Metadata documentation](metadata.md).

## Using a Domain Name with Github Pages and Couscous

To use a CNAME for your Couscous-generated documentation so that your docs point to `docs.yourdomain.com` or something similar, first create the CNAME file and point your DNS to `yourname.github.io`, as detailed in the [Github documentation](https://help.github.com/articles/setting-up-a-custom-domain-with-github-pages/).

To deploy the CNAME file to your `gh-pages` branch automatically with Couscous, simply put the CNAME file in the `website` directory (or whatever template directory you configured) in `couscous.yml`.

Note that this will only work if the you're using embedded templates (versus remote templates). If you're using remote templates, you'll need to copy the remote templates to your repository and configure your `couscous.yml` file to use the embedded template instead.
