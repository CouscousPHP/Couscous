---
current_menu: templates
---
# Templates

## Remote templates

A **remote template** is a template that is hosted separately in a git repository.
It allows many projects to reuse the same template.

To write a remote template, just write a normal template inside a directory (read below for understanding how).
Then publish that directory online (for example on GitHub).

You can find some examples of templates [here](https://couscous.io/templates.html).
The [Basic](https://github.com/CouscousPHP/Template-Basic) template is a good way to start.

*ProTip:* To preview your template, you can use `couscous preview` (Couscous will use your template's Readme). In order to tell Couscous that the template is in the root of the repository (and not in a `website/` subdirectory), use the following configuration:

```yaml
template:
    directory: .
```

## Embedded templates

Templates contain Twig layouts and assets (javascripts, css…). They should be stored inside a `website/` directory (or [whatever you configured](configuration.md)) in your project.

### Default layout

The default Twig layout that is used for rendering the pages should be named `default.twig`.

Example of a `default.twig`:

```html
<!DOCTYPE html>
<html>
    <head>
        <title>My project!</title>
    </head>
    <body>
        {% block content %}

            {{ content|raw }}

        {% endblock %}
    </body>
</html>
```

The only variable you can use by default is `content`. This variable contains the content of the Markdown file rendered to HTML.

### Additional layouts

If, for example, you want your home page to have a different layout, you can write a `home.twig`
that overrides `default.twig`:

```html
{% extends "default.twig" %}

{% block content %}
    <h1>This is the home page!</h1>

    {{ content|raw }}
{% endblock %}
```

You can set your `README.md` (i.e. your home page) to use that layout using [YAML front matter](https://jekyllrb.com/docs/frontmatter/) in the Markdown file:

```markdown
---
layout: home
---
This is my documentation.

## Subtitle

This is a *sub-chapter*.
```

### Variables (aka Metadata)

Custom variables, called [**Metadata**](metadata.md), can be defined in:

- `couscous.yml`
- YAML front matter (at the top of Markdown files)

Those variables are accessible in the Twig layouts, for example:

```yaml
# couscous.yml
title: "This is the website title!"
```

```markdown
---
category: "Star Wars"
---
This is my documentation.
```

```html
<!DOCTYPE html>
<html>
    <head>
        <title>{{ title }}</title>
    </head>
    <body>
        {% block content %}

            <h1>Category: {{ category }}</h1>

            {{ content|raw }}

        {% endblock %}
    </body>
</html>
```

To learn more, read the whole [Metadata documentation](metadata.md).

### Links

To ensure all your links are working correctly, you should define a `baseUrl` variable in `couscous.yml`
(see [the configuration documentation](configuration.md)).

Then you can use it in the layouts:

```html
<!DOCTYPE html>
<html>
    <body>

        <ul class="menu">
            <li>
                <a href="{{ baseUrl }}/doc/my-sub-article.md">Sub-article</a>
            </li>
        </ul>

        {% block content %}

            {{ content|raw }}

        {% endblock %}
    </body>
</html>
```

All your Markdown links will be rewritten by Couscous to work. However, make sure you write relative links.
A good rule of thumb is: **if it works on GitHub.com, it will work with Couscous**.

## Dependencies

If a `package.json` or a `bower.json` file is present in the `website/` directory, dependencies will be
installed automatically by using `yarn`, `npm` or `bower`.

In that case, you need to have one of these tools installed. If you don't have a dependency file, you don't need to install one of them.
