<?php

namespace Couscous\Module\Scripts\Step;

use Couscous\CommandException;
use Couscous\CommandRunner;
use Couscous\Model\Repository;
use Symfony\Component\Console\Output\OutputInterface;

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

    public function __construct(CommandRunner $commandRunner)
    {
        $this->commandRunner = $commandRunner;
    }

    protected function executeScripts($scripts, Repository $repository, OutputInterface $output)
    {
        if (empty($scripts)) {
            return;
        }

        foreach ($scripts as $script) {
            $this->executeScript($output, $repository->sourceDirectory, $script);
        }
    }

    private function executeScript(OutputInterface $output, $sourceDirectory, $script)
    {
        $script = 'cd "' . $sourceDirectory . '" && ' . $script;

        $output->writeln("Executing <info>$script</info>");

        try {
            $this->commandRunner->run($script);
        } catch (CommandException $e) {
            throw new \RuntimeException(
                "Error while running '$script':" . PHP_EOL . $e->getMessage()
            );
        }
    }
}
