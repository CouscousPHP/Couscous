<?php

namespace Couscous\Module\Markdown\Step;

use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Model\Repository;
use Couscous\Module\Markdown\Model\TableOfContents;
use Couscous\Step;
use League\CommonMark\Block\Element\Document;
use League\CommonMark\Block\Element\Header;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Turns Markdown to HTML.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ExtractTableOfContents implements Step
{
    /**
     * @var DocParser
     */
    private $markdownParser;

    public function __construct()
    {
        $environment = Environment::createCommonMarkEnvironment();
        $this->markdownParser = new DocParser($environment);
        $this->htmlRenderer = new HtmlRenderer($environment);
    }

    public function __invoke(Repository $repository, OutputInterface $output)
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
     * @param Document $document
     * @return TableOfContents
     */
    private function parseTableOfContents(Document $document)
    {
        $title = null;
        $headers = [];

        foreach ($document->getChildren() as $block) {
            if ($block instanceof Header) {
                $html = $this->htmlRenderer->renderBlock($block);
                $headers[$block->getLevel()][] = strip_tags($html);
            }
        }

        $toc = new TableOfContents();
        $toc->setAllHeaders($headers);

        return $toc;
    }
}
