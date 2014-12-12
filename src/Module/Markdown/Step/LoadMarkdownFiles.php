<?php

namespace Couscous\Module\Markdown\Step;

use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Model\Repository;
use Couscous\Step;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Loads Markdown files in memory.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LoadMarkdownFiles implements \Couscous\Step
{
    public function __invoke(Repository $repository, OutputInterface $output)
    {
        $files = $repository->sourceFiles();
        $files->name('*.md');

        foreach ($files as $file) {
            /** @var SplFileInfo $file */
            $content = file_get_contents($file->getPathname());

            $repository->addFile(new MarkdownFile($file->getRelativePathname(), $content));
        }

        $repository->watchlist->watchFiles($files);
    }
}
