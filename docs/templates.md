# Templates

Templates are Twig templates, and should all in the root of the `website` directory (or whatever you named it).

The default template that is used for rendering the pages is in `page.twig`.

Example of a `page.twig`:

```html
<!DOCTYPE html>
<html lang="en">
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

If, for example, you want your home page to have a different template, you can write a `home.twig`
that overrides `page.twig`:

```html
{% extends "page.twig" %}

{% block content %}
    <h1>This is the home page!</h1>

    {{ content|raw }}
{% endblock %}
```

You can set your `README.md` (i.e. your home page) to use that template using YAML Front matter in the Markdown file:

```markdown
---
template: home
---
This is my documentation.

## Subtitle

This is a *sub-chapter*.
```

**Good to know: any variable you put in the YAML Front matter is accessible in the view.**

Example:

```markdown
---
template: home
myVar: true
myOtherVar: "Some string"
---
This is my documentation.
```


## Links

To ensure all your links are working correctly, you should define a `baseUrl` in `configuration.yml`
(see [the configuration documentation](configuration.md)).

Then you can use it in the templates:

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

All your Markdown links should be rewritten and work. However, make sure you write relative links.
A good rule of thumb is: if it works on GitHub.com, it will work with Couscous.
