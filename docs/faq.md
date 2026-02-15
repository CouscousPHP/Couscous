---
current_menu: faq
---
# Frequently asked questions

#### Does Couscous supports GitHub fenced code blocks in Markdown?

Yes.

#### Do links works between pages?

Yes, they work just like on GitHub.com.

Links in Markdown are rewritten to link to HTML pages:

- A link to `something.md` will be rewritten to `something.html`;
- A link to `Something-Else.md` will be rewritten to `something-else.html`;
- A link to `CONTRIBUTING.md` will be rewritten to `contributing.html`;
- A link to `README.md` will be rewritten to `index.html`.

#### Can I use it for a blog?

Probably not yet, but you are welcome to try. [PHP-DI's website](https://php-di.org/news/) has a pseudo-blog where the article list is written manually: it's possible, but not (yet) super easy.

#### Can I deploy the website to something else than GitHub Pages?

It is planned! In the meantime, you can simply use `couscous generate` and upload the files to your webserver manually.

#### Can I add twig extensions?

Yes. In your template directory. Add a file called `twig.php` with the following contents: 

```php
<?php

use Twig\Environment;

// Use "include_once" since this file may be called multiple times
include_once dirname(__DIR__).'/Twig/MyTwigExtension.php';

return function (Environment $twig) {
    $twig->addExtension(new MyTwigExtension());
};
```

#### Why not build Couscous on top of Sculpin?

I tried.

#### Why is it named Couscous?

Because Couscous is good, and so is this.
