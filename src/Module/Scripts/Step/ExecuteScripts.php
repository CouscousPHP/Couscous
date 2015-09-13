<?php

namespace Couscous\Module\Scripts\Step;

use Couscous\CommandRunner\CommandException;
use Couscous\CommandRunner\CommandRunner;
use Couscous\Model\Project;
use Psr\Log\LoggerInterface;

/**
 * Base class.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
abstract class ExecuteScripts
{
    /**
     * @var CommandRunner
     */
    private $commandRunner;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(CommandRunner $commandRunner, LoggerInterface $logger)
    {
        $this->commandRunner = $commandRunner;
        $this->logger = $logger;
    }

    protected function executeScripts($scripts, Project $project)
    {
        if (empty($scripts)) {
            return;
        }

        foreach ($scripts as $script) {
            $this->executeScript($project->sourceDirectory, $script);
        }
    }

    private function executeScript($sourceDirectory, $script)
    {
        $script = 'cd "'.$sourceDirectory.'" && '.$script;

        $this->logger->notice('Executing {script}', ['script' => $script]);

        try {
            $this->commandRunner->run($script);
        } catch (CommandException $e) {
            throw new \RuntimeException(
                "Error while running '$script':".PHP_EOL.$e->getMessage()
            );
        }
    }
}
