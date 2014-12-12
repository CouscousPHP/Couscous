<?php

namespace Couscous\Module\Core\Step;

use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Clear the generation target directory.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ClearTargetDirectory implements StepInterface
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
        $files = new Finder();
        $files->in($repository->targetDirectory);

        $this->filesystem->remove($files);
    }
}
