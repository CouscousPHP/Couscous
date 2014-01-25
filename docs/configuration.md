# Configuration with couscous.yml

You can define options in a `couscous.yml` file at the root of your repository.

That configuration file is optional.

Reference:

```yaml
# Base URL of the published website (no "/" at the end!)
baseUrl: http://mnapoli.github.io/Couscous

# Name of the directory containing the website template (default is "website")
directory: website

# List of directories to exclude from the processing (default contains "vendor")
# Paths are relative to the repository root
exclude:
  - vendor
  - some/dir

# Scripts to execute before generating the website
before:
  - cp bin/couscous.phar website/public/

# Scripts to execute after generating the website
after:
  - rm website/public/couscous.phar
```
