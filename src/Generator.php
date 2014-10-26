<?php

namespace Couscous;

use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Generates the website.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class Generator
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var StepInterface[]
     */
    private $steps;

    public function __construct(Filesystem $filesystem, array $steps)
    {
        $this->filesystem = $filesystem;
        $this->steps = $steps;
    }

    public function generate(Repository $repository, OutputInterface $output)
    {
        $output->writeln(sprintf(
            "<comment>Generating %s to %s</comment>",
            $repository->sourceDirectory,
            $repository->targetDirectory
        ));

        $this->filesystem->mkdir($repository->targetDirectory);

        foreach ($this->steps as $step) {
            $step->__invoke($repository, $output);
        }
    }
}
