<?php

namespace Couscous\Step\Template;

use Couscous\Model\File\LazyFile;
use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;
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
class LoadAssets implements StepInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function __invoke(Repository $repository, OutputInterface $output)
    {
        if (! $repository->metadata['template.directory']) {
            return;
        }

        $files = new Finder();
        $files->files()
            ->in($repository->metadata['template.directory'])
            ->ignoreDotFiles(true)
            ->notName('*.twig');

        $repository->watchlist->watchFiles($files);

        foreach ($files as $file) {
            /** @var SplFileInfo $file */
            $repository->addFile(new LazyFile($file->getPathname(), $file->getRelativePathname()));
        }
    }
}
