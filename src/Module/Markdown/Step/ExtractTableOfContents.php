<?php

namespace Couscous\Module\Markdown\Step;

use Couscous\Model\Project;
use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Module\Markdown\Model\TableOfContents;
use Couscous\Step;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\Block\Element\Heading;
use League\CommonMark\DocParser;
use League\CommonMark\HtmlRenderer;

/**
 * Extract the table of content from headers in the document.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ExtractTableOfContents implements Step
{
    /**
     * @var DocParser
     */
    private $markdownParser;

    /**
     * @var HtmlRenderer
     */
    private $htmlRenderer;

    public function __construct(DocParser $markdownParser, HtmlRenderer $htmlRenderer)
    {
        $this->markdownParser = $markdownParser;
        $this->htmlRenderer = $htmlRenderer;
    }

    public function __invoke(Project $repository)
    {
        /** @var MarkdownFile[] $files */
        $files = $repository->findFilesByType('Couscous\Module\Markdown\Model\MarkdownFile');

        foreach ($files as $file) {
            $document = $this->markdownParser->parse($file->getContent());

            $tableOfContents = $this->parseTableOfContents($document);

            $file->getMetadata()['tableOfContents'] = $tableOfContents;
        }
    }

    /**
     * @return TableOfContents
     */
    private function parseTableOfContents(Document $document)
    {
        $toc = new TableOfContents();

        // Iterate only root blocks to find headers
        foreach ($document->children() as $node) {
            if ($node instanceof Heading) {
                $html = $this->htmlRenderer->renderBlock($node);
                $toc->addHeader($node->getLevel(), trim(strip_tags($html)));
            }
        }

        return $toc;
    }
}
