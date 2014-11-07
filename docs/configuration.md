# Configuration with couscous.yml

You can define options in a `couscous.yml` file at the root of your repository.

That configuration file is optional.

Reference:

```yaml
# Name of the directory containing the website template (default is "website")
directory: website

# List of directories to exclude from the processing (default contains "vendor")
# Paths are relative to the repository root
exclude:
    - vendor
    - some/dir

# Scripts to execute before generating the website
before:
    - cp bin/couscous.phar website/

# Scripts to execute after generating the website
after:
    - rm website/couscous.phar

# Any variable you put in "template" is available in the Twig layouts
template:

    title: Hello!

    # Base URL of the published website (no "/" at the end!)
    # You are advised to set and use this variable to write your links in the HTML layouts
    baseUrl: http://username.github.io/your-project
```
