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

            $project->addFile($markdownFile);
        }
    }

    private function renameFileExtension(MarkdownFile $file)
    {
        $file->relativeFilename = $this->replaceExtension($file->relativeFilename);
    }

    private function renameReadme(MarkdownFile $file)
    {
        $filename = basename($file->relativeFilename);

        if ($filename === 'README.html') {
            $path = dirname($file->relativeFilename);
            $path = ($path === '.') ? '' : $path . '/';

            $file->relativeFilename = $path . 'index.html';
        }
    }

    private function replaceExtension($filename)
    {
        $filename = substr($filename, 0, strrpos($filename, '.'));

        return $filename . '.html';
    }
}
