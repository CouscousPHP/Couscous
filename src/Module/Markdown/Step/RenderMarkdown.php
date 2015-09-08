<?php

namespace Couscous\Module\Markdown\Step;

use Couscous\Model\Project;
use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Module\Template\Model\HtmlFile;
use Couscous\Step;
use Mni\FrontYAML\Parser;

/**
 * Turns Markdown to HTML.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class RenderMarkdown implements Step
{
    /**
     * @var Parser
     */
    private $markdownParser;

    public function __construct(Parser $markdownParser)
    {
        $this->markdownParser = $markdownParser;
    }

    public function __invoke(Project $project)
    {
        /** @var MarkdownFile[] $markdownFiles */
        $markdownFiles = $project->findFilesByType('Couscous\Module\Markdown\Model\MarkdownFile');

        foreach ($markdownFiles as $markdownFile) {
            $htmlFile = $this->renderFile($markdownFile);

            $project->replaceFile($markdownFile, $htmlFile);
        }
    }

    private function renderFile(MarkdownFile $file)
    {
        $document = $this->markdownParser->parse($file->getContent());

        $filename = $this->replaceExtension($file->relativeFilename);

        return new HtmlFile($filename, $document->getContent(), $file);
    }

    private function replaceExtension($filename)
    {
        $filename = substr($filename, 0, strrpos($filename, '.'));

        return $filename.'.html';
    }
}
