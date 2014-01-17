<?php

namespace Couscous\Processor;

use Couscous\Page;

/**
 * Processes the file name.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FileNameProcessor implements Processor
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
