---
current_menu: metadata
---
# Metadata

In Couscous, there are 2 kinds of data:

- **file content** (e.g. Markdown, HTML, …)
- **metadata**, which is simply an array of variables

Metadata can contain any kind of value and can be attached to:

- the whole project
- a specific file

Metadata is used by Couscous to customize the generation process and by templates to customize their content.

## Defining metadata

**Project** metadata is defined in `couscous.yml`. For example:

```yaml
template:
    directory: website
title: The website title!
```

**File** metadata is defined using YAML front matter (at the top of Markdown files):

```markdown
---
category: "Star Wars"
---
This is my documentation.
```

However, Couscous will also define both **project and file metadata** while generating the website. For example, when parsing Markdown files, it will define the `content` variable (to contain the generated HTML content for the current file).

## Metadata reference

Here is a list of **all metadata variables automatically defined by Couscous**:

- `content`: contains the generated HTML content of the current file (Markdown turned to HTML)
- `currentFile`: contains the relative name of the current file (e.g. the name of the Markdown file before it is turned to a HTML file)

Remember everything you defined in `couscous.yml` or in YAML front matter is also available.
