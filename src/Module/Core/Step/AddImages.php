<?php
declare(strict_types = 1);

namespace Couscous\Module\Core\Step;

use Couscous\Model\LazyFile;
use Couscous\Model\Project;
use Couscous\Step;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Add images to the project.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class AddImages implements Step
{
    /**
     * Add images to the given project.
     */
    public function __invoke(Project $project): void
    {
        $files = $project->sourceFiles();
        $files
            ->name('*.gif')
            ->name('*.png')
            ->name('*.jpg')
            ->name('*.jpeg')
            ->name('*.svg');

        $project->watchlist->watchFiles($files);

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $project->addFile(new LazyFile($file->getPathname(), $file->getRelativePathname()));
        }
    }
}
