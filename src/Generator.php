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

    private function processTemplate(GenerationHelper $generation, Filesystem $filesystem)
    {
        $templateUrl = $generation->config->templateUrl;

        if ($templateUrl !== null) {
            // Template is in a git repo
            $generation->output->writeln("Fetching template from <info>$templateUrl</info>");

            $templateDirectory = $generation->tempDirectory . '/template';
            if (file_exists($templateDirectory)) {
                $command = "cd $templateDirectory && git pull 2>&1";
            } else {
                $command = "git clone $templateUrl $templateDirectory 2>&1";
            }
            $gitOutput = array();
            exec($command, $gitOutput, $returnValue);
            if ($returnValue !== 0) {
                throw new \RuntimeException(implode(PHP_EOL, $gitOutput));
            }
        } else {
            // Template is in a directory
            $templateDirectory = $generation->sourceDirectory . '/' . $generation->config->directory;

            if (! $filesystem->exists($templateDirectory)) {
                throw new \InvalidArgumentException("The template directory doesn't exist: $templateDirectory");
            }
        }

        $generation->output->writeln('Copying template files');
        $filesystem->mirror($templateDirectory . '/public', $generation->targetDirectory, null, array('delete' => true));

        return $templateDirectory;
    }
}
