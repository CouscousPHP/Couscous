<?php

namespace Couscous\Module\Markdown\Step;

use Couscous\Model\Project;
use Couscous\Module\Markdown\Model\MarkdownFile;
use Couscous\Step;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Loads Markdown files in memory.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LoadMarkdownFiles implements Step
{
    public function __invoke(Project $project)
    {
        $files = $project->sourceFiles();
        $files->name('*.md');

        foreach ($files as $file) {
            /** @var SplFileInfo $file */
            $content = file_get_contents($file->getPathname());

            $project->addFile(new MarkdownFile($file->getRelativePathname(), $content));
        }

        $project->watchlist->watchFiles($files);
    }
}
