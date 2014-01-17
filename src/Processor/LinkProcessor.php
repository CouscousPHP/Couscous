<?php

namespace Couscous\Processor;

use Couscous\Page;

/**
 * Processes links to *.md (markdown) files.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LinkProcessor implements Processor
{
    /**
     * {@inheritdoc}
     */
    public function process(Page $page)
    {
        $page->content = preg_replace('/<a href="([^"]*)\.md"/', '<a href="$1.html"', $page->content);
    }
}
