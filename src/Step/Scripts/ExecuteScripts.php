<?php

namespace Couscous\Step\Scripts;

use Couscous\Model\Repository;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Base class.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
abstract class ExecuteScripts
{
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

        exec($script, $scriptOutput, $returnValue);

        if ($returnValue !== 0) {
            throw new \RuntimeException(
                "Error while running '$script':" . PHP_EOL . implode(PHP_EOL, $scriptOutput)
            );
        }
    }
}
