<?php

namespace Couscous\Module\Core\Step;

use Couscous\Model\LazyFile;
use Couscous\Model\Project;
use Couscous\Step;

/**
 * Add images to the project.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class AddImages implements Step
{
    /**
     * Add images to the given project.
     *
     * @param Project $project
     */
    public function __invoke(Project $project)
    {
        $files = $project->sourceFiles();
        $files
            ->name('*.gif')
            ->name('*.png')
            ->name('*.jpg')
            ->name('*.jpeg')
            ->name('*.svg');

        $project->watchlist->watchFiles($files);

        foreach ($files as $file) {
            /** @var SplFileInfo $file */
            $project->addFile(new LazyFile($file->getPathname(), $file->getRelativePathname()));
        }
    }
}
