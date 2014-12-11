<?php

namespace Couscous\Step\Template;

use Couscous\CommandRunner;
use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Fetch a remote template.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class FetchRemoteTemplate implements StepInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var CommandRunner
     */
    private $commandRunner;

    /**
     * Temporarily save the template directory if we are in preview
     * to avoid cloning the repository every time.
     *
     * In theory we shouldn't store state in this object because it's a service
     * but we need extensive change to avoid that.
     *
     * @var string
     */
    private $templateDirectory;

    public function __construct(Filesystem $filesystem, CommandRunner $commandRunner)
    {
        $this->filesystem = $filesystem;
        $this->commandRunner = $commandRunner;
    }

    public function __invoke(Repository $repository, OutputInterface $output)
    {
        // In preview we avoid cloning the repository every time
        if ($repository->regenerate && $this->templateDirectory) {
            $repository->metadata['template.directory'] = $this->templateDirectory;

            return;
        }

        $templateUrl = $repository->metadata['template.url'];

        if ($templateUrl === null) {
            return;
        }

        $directory = $this->fetchGitTemplate($templateUrl, $output);

        $this->templateDirectory = $directory;
        $repository->metadata['template.directory'] = $directory;
    }

    private function fetchGitTemplate($gitUrl, OutputInterface $output)
    {
        $output->writeln("Fetching template from <info>$gitUrl</info>");

        $directory = $this->createTempDirectory('couscous_template_');

        $this->commandRunner->run("git clone $gitUrl $directory 2>&1");

        return $directory;
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
