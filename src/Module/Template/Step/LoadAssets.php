<?php
declare(strict_types = 1);

namespace Couscous\Module\Template\Step;

use Couscous\Model\LazyFile;
use Couscous\Model\Project;
use Couscous\Step;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Load asset files from the template directory.
 *
 * Assets are any file that is not a Twig template.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LoadAssets implements Step
{
    public function __invoke(Project $project): void
    {
        if (!$project->metadata['template.directory']) {
            return;
        }

        $files = new Finder();
        $files->files()
            ->in((string) $project->metadata['template.directory'])
            ->ignoreDotFiles(false)
            ->notName('*.twig')
            ->notName('*.md')
            ->notName('couscous.yml');

        $project->watchlist->watchFiles($files);

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $project->addFile(new LazyFile($file->getPathname(), $file->getRelativePathname()));
        }
    }
}
