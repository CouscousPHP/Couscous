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

    private $project;

    public function __invoke(Project $project)
    {

        $this->project = $project;

        /** @var MarkdownFile[] $markdownFiles */
        $markdownFiles = $project->findFilesByType('Couscous\Module\Markdown\Model\MarkdownFile');

        foreach ($markdownFiles as $markdownFile) {
            $project->removeFile($markdownFile);

            $this->renameFileExtension($markdownFile);
            $this->renameReadme($markdownFile);
            $this->renameFilename($markdownFile);

            $project->addFile($markdownFile);
        }
    }

    private function renameFileExtension(MarkdownFile $file)
    {
        $file->relativeFilename = $this->replaceExtension($file->relativeFilename);
    }

    private function renameReadme(MarkdownFile $file)
    {

        if(
          !empty($this->project->metadata['index']) &&
          $file->getBasename() !== $this->replaceExtension(basename($this->project->metadata['index'])))
        {
          return;
        } else if(empty($this->project->metadata['index']) && $file->getBasename() !== 'README.html') {
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
