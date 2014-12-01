<?php

namespace Couscous\Step;

use Couscous\Model\Repository;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Execute the scripts that were set in "after" in the configuration.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ExecuteAfterScripts implements StepInterface
{
    public function __invoke(Repository $repository, OutputInterface $output)
    {
        $scripts = $repository->metadata['scripts.after'];

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
