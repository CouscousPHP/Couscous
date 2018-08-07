<?php

namespace Couscous\Module\Markdown\Step;

use Couscous\Model\Project;
use Couscous\Module\Markdown\Model\MarkdownFile;
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
            $this->renameReadme($markdownFile, $project);
            $this->renameFilename($markdownFile);

            $project->addFile($markdownFile);
        }
    }

    private function renameFileExtension(MarkdownFile $file)
    {
        $file->relativeFilename = $this->replaceExtension($file->relativeFilename);
    }

    private function renameReadme(MarkdownFile $file, Project $project)
    {
        $indexFile = empty($project->metadata['template']['index'])
            ? 'README.md'
            : $project->metadata['template']['index'];
        $indexFile = $this->replaceExtension(basename($indexFile));
        if ($file->getBasename() !== $indexFile) {
            return;
        }

        $file->relativeFilename = $file->getDirectory().'index.html';
    }

    private function renameFilename(MarkdownFile $file)
    {
        $basename = $file->getBasename();
        if (!preg_match('/[A-Z]/', $basename)) {
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
