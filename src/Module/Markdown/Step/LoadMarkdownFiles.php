<?php
declare(strict_types = 1);

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
    public function __invoke(Project $project): void
    {
        $files = $project->sourceFiles();
        $files->name('*.md');

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $content = file_get_contents($file->getPathname());

            $project->addFile(new MarkdownFile($file->getRelativePathname(), $content));
        }

        $project->watchlist->watchFiles($files);
    }
}
