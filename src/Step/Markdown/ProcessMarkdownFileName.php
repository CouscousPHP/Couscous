<?php

namespace Couscous\Step\Markdown;

use Couscous\Model\MarkdownFile;
use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Processes the name of Markdown files.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ProcessMarkdownFileName implements StepInterface
{
    public function __invoke(Repository $repository, OutputInterface $output)
    {
        /** @var MarkdownFile[] $markdownFiles */
        $markdownFiles = $repository->findFilesByType('Couscous\Model\MarkdownFile');

        foreach ($markdownFiles as $markdownFile) {
            $repository->removeFile($markdownFile);

            $this->renameFileExtension($markdownFile);
            $this->renameReadme($markdownFile);

            $repository->addFile($markdownFile);
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
