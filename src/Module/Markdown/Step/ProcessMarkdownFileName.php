<?php

namespace Couscous\Module\Markdown\Step;

use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Model\Project;
use Couscous\Step;

/**
 * Processes the name of Markdown files.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ProcessMarkdownFileName implements Step
{
    public function __invoke(Project $project)
    {
        /** @var MarkdownFile[] $markdownFiles */
        $markdownFiles = $project->findFilesByType('Couscous\Module\Markdown\Model\MarkdownFile');

        foreach ($markdownFiles as $markdownFile) {
            $project->removeFile($markdownFile);

            $this->renameFileExtension($markdownFile);
            $this->renameReadme($markdownFile);
            $this->renameUppercase($markdownFile);

            $project->addFile($markdownFile);
        }
    }

    private function renameFileExtension(MarkdownFile $file)
    {
        $file->relativeFilename = $this->replaceExtension($file->relativeFilename);
    }

    private function renameReadme(MarkdownFile $file)
    {
        if ($file->getBasename() !== 'README.html') {
            return;
        }

        $file->relativeFilename = $file->getDirectory().'index.html';
    }

    private function renameUppercase(MarkdownFile $file)
    {
        $basename = $file->getBasename();
        if (!preg_match('/^[A-Z0-9_-]+\.html$/', $basename)) {
            return;
        }

        $file->relativeFilename = $file->getDirectory().strtolower($basename);
    }

    private function replaceExtension($filename)
    {
        $filename = substr($filename, 0, strrpos($filename, '.'));

        return $filename.'.html';
    }
}
