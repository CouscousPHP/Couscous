<?php

namespace Couscous\Module\Template\Step;

use Couscous\Model\LazyFile;
use Couscous\Model\Project;
use Couscous\Step;
use Symfony\Component\Filesystem\Filesystem;
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
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function __invoke(Project $project)
    {
        if (!$project->metadata['template.directory']) {
            return;
        }

        $files = new Finder();
        $files->files()
            ->in($project->metadata['template.directory'])
            ->ignoreDotFiles(false)
            ->notName('*.twig')
            ->notName('*.md')
            ->notName('couscous.yml');

        $project->watchlist->watchFiles($files);

        foreach ($files as $file) {
            /** @var SplFileInfo $file */
            $project->addFile(new LazyFile($file->getPathname(), $file->getRelativePathname()));
        }
    }
}
