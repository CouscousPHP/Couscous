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

# List of directories to include in the processing (by default it's empty, so all markdown files are parsed)
# Paths are relative to the optional source path given when generating the website, repository root by default
include:
    - docs

# List of directories to exclude from the processing (default contains "vendor" and "website")
# Paths are relative to the optional include paths given when generating the website, repository root by default
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

# Set this variable to use a Custom Domain
# The content of this variable will be directly inserted into the CNAME file
cname: docs.yourdomain.com

# Set the target branch in which to deploy the generated website
branch: gh-pages

# Any variable you put in this file is also available in the Twig layouts:
title: Hello!

# Base URL of the published website (no "/" at the end!)
# You are advised to set and use this variable to write your links in the HTML layouts
baseUrl: http://username.github.io/your-project
```

**Note:** any variable you put in `couscous.yml` is called **Metadata**. You can use these variables in templates for example. Learn more about this in the [Metadata documentation](metadata.md).

## Using a Domain Name with Github Pages and Couscous

To use a CNAME for your Couscous-generated documentation so that your docs point to `docs.yourdomain.com` or something similar, set the `cname` variable described above and point your DNS to `yourname.github.io`, as detailed in the [Github documentation](https://help.github.com/articles/setting-up-a-custom-domain-with-github-pages/).
