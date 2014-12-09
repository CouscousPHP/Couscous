<?php

namespace Couscous\Step\Template;

use Couscous\CommandRunner;
use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Initializes the website template.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class InitTemplate implements StepInterface
{
    const DEFAULT_TEMPLATE_DIRECTORY = 'website';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var CommandRunner
     */
    private $command_runner;

    public function __construct(Filesystem $filesystem, CommandRunner $commandRunner)
    {
        $this->filesystem       = $filesystem;
        $this->command_runner   = $commandRunner;
    }

    public function __invoke(Repository $repository, OutputInterface $output)
    {
        $templateUrl = $repository->metadata['template.url'];

        if ($templateUrl !== null) {
            $this->templateFromGitUrl($repository, $templateUrl, $output);
        } else {
            $this->templateFromDirectory($repository);
        }
    }

    private function templateFromDirectory(Repository $repository)
    {
        if (! is_null($repository->metadata['template.directory'])) {
            $templateDirectory = $repository->sourceDirectory . '/'
                . $repository->metadata['template.directory'];

            if (!$this->filesystem->exists($templateDirectory)) {
                throw new \RuntimeException(sprintf(
                    "The template directory '%s' doesn't exist",
                    $templateDirectory
                ));
            }
        } else {
            $templateDirectory = $repository->sourceDirectory . '/' . self::DEFAULT_TEMPLATE_DIRECTORY;

            if (! $this->filesystem->exists($templateDirectory)) {
                throw new \RuntimeException(sprintf(
                    'The template directory %s does not exist',
                    $templateDirectory
                ));
            }
        }

        $repository->watchlist->watchDirectory($templateDirectory);

        $repository->metadata['template.directory'] = $templateDirectory;
    }

    private function templateFromGitUrl(Repository $repository, $gitUrl, OutputInterface $output)
    {
        $output->writeln("Fetching template from <info>$gitUrl</info>");

        $templateDirectory = $this->createTempDirectory('couscous_template_');

        $this->command_runner->run("git clone $gitUrl $templateDirectory");

        $repository->metadata['template.directory'] = $templateDirectory;
    }

    private function createTempDirectory($prefix)
    {
        $tempFile = tempnam(sys_get_temp_dir(), $prefix);
        // Turn the temp file into a temp directory
        $this->filesystem->remove($tempFile);
        $this->filesystem->mkdir($tempFile);

        return $tempFile;
    }
}
