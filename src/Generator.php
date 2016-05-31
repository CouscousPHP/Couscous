<?php

namespace Couscous;

use Couscous\Model\Project;
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
     * @var Step[]
     */
    private $steps;

    /**
     * @param Filesystem $filesystem
     * @param Step[]     $steps
     */
    public function __construct(Filesystem $filesystem, array $steps)
    {
        $this->filesystem = $filesystem;
        $this->steps = $steps;
    }

    public function generate(Project $project, OutputInterface $output)
    {
        $output->writeln(sprintf(
            '<comment>Generating %s to %s</comment>',
            $project->sourceDirectory,
            $project->targetDirectory
        ));

        $this->filesystem->mkdir($project->targetDirectory);

        foreach ($this->steps as $step) {
            $step->__invoke($project);
        }
    }
}
