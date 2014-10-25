<?php

namespace Couscous\Processor\Markdown;

use Couscous\Page;
use Couscous\Processor\Processor;

/**
 * Processes links to *.md (markdown) files.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class MarkdownLinkProcessor implements Processor
{
    /**
     * {@inheritdoc}
     */
    public function process(Page $page)
    {
        $page->content = preg_replace('/<a href="([^"]*)\.md"/', '<a href="$1.html"', $page->content);
    }
}
