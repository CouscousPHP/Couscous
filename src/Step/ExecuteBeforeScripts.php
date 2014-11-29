<?php

namespace Couscous\Step;

use Couscous\Model\Repository;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Execute the scripts that were set in "before" in the configuration.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ExecuteBeforeScripts implements StepInterface
{
    public function __invoke(Repository $repository, OutputInterface $output)
    {
        $scripts = $repository->metadata['before'];

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
