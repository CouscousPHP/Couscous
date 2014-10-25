<?php

namespace Couscous\Processor\Markdown;

use Couscous\Page;
use Couscous\Processor\Processor;

/**
 * Processes the name of Markdown files.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class MarkdownFileNameProcessor implements Processor
{
    /**
     * {@inheritdoc}
     */
    public function process(Page $page)
    {
        $page->filename = basename($page->filename, '.md') . '.html';

        if ($page->filename === 'README.html') {
            $page->filename = 'index.html';
        }
    }
}
