<?php

namespace Couscous\Step\Template;

use Couscous\Model\Repository;
use Couscous\Model\Template;
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
    const TEMPLATE_DIRECTORY = 'website';
    const PUBLIC_DIRECTORY = 'public';

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
        $templateUrl = $repository->config->templateUrl;

        if ($templateUrl !== null) {
            $this->templateFromGitUrl($repository, $templateUrl, $output);
        } else {
            $this->templateFromDirectory($repository);
        }
    }

    private function templateFromDirectory(Repository $repository)
    {
        $templateDirectory = $repository->sourceDirectory . '/' . self::TEMPLATE_DIRECTORY;
        $publicDirectory = $templateDirectory . '/' . self::PUBLIC_DIRECTORY;

        if (! $this->filesystem->exists($templateDirectory)) {
            throw new \RuntimeException("The template directory doesn't exist: $templateDirectory");
        }

        $repository->template = new Template($templateDirectory, $publicDirectory);
    }

    private function templateFromGitUrl(Repository $repository, $gitUrl, OutputInterface $output)
    {
        $output->writeln("Fetching template from <info>$gitUrl</info>");

        $templateDirectory = $this->createTempDirectory('couscous_template_');

        $command = "git clone $gitUrl $templateDirectory 2>&1";
        exec($command, $gitOutput, $returnValue);
        if ($returnValue !== 0) {
            throw new \RuntimeException(implode(PHP_EOL, $gitOutput));
        }

        $publicDirectory = $templateDirectory . '/' . self::PUBLIC_DIRECTORY;
        $repository->template = new Template($templateDirectory, $publicDirectory);
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
