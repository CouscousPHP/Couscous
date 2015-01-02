<?php

namespace Couscous\Module\Bower\Step;

use Couscous\CommandRunner\CommandRunner;
use Couscous\Model\Repository;
use Couscous\Step;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Run Bower install.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class RunBowerInstall implements Step
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
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Filesystem $filesystem,
        CommandRunner $commandRunner,
        LoggerInterface $logger
    ) {
        $this->filesystem = $filesystem;
        $this->commandRunner = $commandRunner;
        $this->logger = $logger;
    }

    public function __invoke(Repository $repository)
    {
        if ($repository->regenerate || ! $this->hasBowerJson($repository)) {
            return;
        }

        $this->logger->notice('Executing "bower install"');

        $result = $this->commandRunner->run(sprintf(
            'cd "%s" && bower install',
            $repository->metadata['template.directory']
        ));

        if ($result) {
            $this->logger->info($result);
        }
    }

    private function hasBowerJson(Repository $repository)
    {
        if (! $repository->metadata['template.directory']) {
            return false;
        }

        $filename = $repository->metadata['template.directory'] . '/bower.json';

        return $this->filesystem->exists($filename);
    }
}
