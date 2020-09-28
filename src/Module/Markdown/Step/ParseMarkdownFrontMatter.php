<?php
declare(strict_types = 1);

namespace Couscous\Module\Markdown\Step;

use Couscous\Model\Project;
use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Step;
use Mni\FrontYAML\Parser;

/**
 * Parse Markdown front matter to load file metadata.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ParseMarkdownFrontMatter implements Step
{
    /**
     * @var Parser
     */
    private $markdownParser;

    public function __construct(Parser $markdownParser)
    {
        $this->markdownParser = $markdownParser;
    }

    public function __invoke(Project $project): void
    {
        /** @var MarkdownFile[] $markdownFiles */
        $markdownFiles = $project->findFilesByType(MarkdownFile::class);

        foreach ($markdownFiles as $file) {
            $document = $this->markdownParser->parse($file->getContent());

            /** @var mixed */
            $metadataValues = $document->getYAML();

            if (is_array($metadataValues)) {
                $file->getMetadata()->setMany($metadataValues);
            }
        }
    }
}
