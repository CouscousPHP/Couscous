<?php

namespace Couscous\Processor\Markdown;

use Couscous\Page;
use Couscous\Processor\Processor;
use Mni\FrontYAML\Parser;

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
        $parser = new Parser();

        $document = $parser->parse($page->content);

        $yaml = $document->getYAML();
        if (is_array($yaml)) {
            foreach ($yaml as $property => $value) {
                $page->$property = $value;
            }
        }

        $page->content = $document->getContent();
    }
}
