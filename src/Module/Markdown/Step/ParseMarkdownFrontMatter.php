<?php

namespace Couscous\Module\Markdown\Step;

use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Mni\FrontYAML\Parser;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Parse Markdown front matter to load file metadata.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ParseMarkdownFrontMatter implements StepInterface
{
    /**
     * @var Parser
     */
    private $markdownParser;

    public function __construct(Parser $markdownParser)
    {
        $this->markdownParser = $markdownParser;
    }

    public function __invoke(Repository $repository, OutputInterface $output)
    {
        /** @var MarkdownFile[] $markdownFiles */
        $markdownFiles = $repository->findFilesByType('Couscous\Module\Markdown\Model\MarkdownFile');

        foreach ($markdownFiles as $file) {
            $document = $this->markdownParser->parse($file->getContent());

            $metadataValues = $document->getYAML();

            if (is_array($metadataValues)) {
                $file->getMetadata()->setMany($metadataValues);
            }
        }
    }
}
