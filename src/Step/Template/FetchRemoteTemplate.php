<?php

namespace Couscous\Step\Template;

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

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function __invoke(Repository $repository, OutputInterface $output)
    {
        $templateUrl = $repository->metadata['template.url'];

        if ($templateUrl === null) {
            return;
        }

        $this->fetchGitTemplate($repository, $templateUrl, $output);
    }

    private function fetchGitTemplate(Repository $repository, $gitUrl, OutputInterface $output)
    {
        $output->writeln("Fetching template from <info>$gitUrl</info>");

        $templateDirectory = $this->createTempDirectory('couscous_template_');

        $command = "git clone $gitUrl $templateDirectory 2>&1";
        exec($command, $gitOutput, $returnValue);
        if ($returnValue !== 0) {
            throw new \RuntimeException(implode(PHP_EOL, $gitOutput));
        }

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
