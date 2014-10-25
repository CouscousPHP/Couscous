<?php

namespace Couscous\Step\Markdown;

use Couscous\Model\HtmlFile;
use Couscous\Model\MarkdownFile;
use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Mni\FrontYAML\Parser;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Turns Markdown to HTML.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class RenderMarkdown implements StepInterface
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
        $markdownFiles = $repository->findFilesByType('Couscous\Model\MarkdownFile');

        foreach ($markdownFiles as $markdownFile) {
            $htmlFile = $this->renderFile($markdownFile);

            $repository->removeFile($markdownFile);
            $repository->addFile($htmlFile);
        }
    }

    private function renderFile(MarkdownFile $file)
    {
        $document = $this->markdownParser->parse($file->getContent());

        $yaml = $document->getYAML();
        $variables = is_array($yaml) ? $yaml : array();

        $filename = $this->replaceExtension($file->relativeFilename);

        return new HtmlFile($filename, $document->getContent(), $variables);
    }

    private function replaceExtension($filename)
    {
        $filename = substr($filename, 0, strrpos($filename, '.'));

        return $filename . '.html';
    }
}
