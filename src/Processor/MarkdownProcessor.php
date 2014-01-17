<?php

namespace Couscous\Processor;

use Couscous\Page;

/**
 * Turns Markdown to HTML.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class MarkdownProcessor implements Processor
{
    /**
     * {@inheritdoc}
     */
    public function process(Page $page)
    {
        $page->content = \Parsedown::instance()->parse($page->content);
    }
}
