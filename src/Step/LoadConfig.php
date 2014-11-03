<?php

namespace Couscous\Step;

use Couscous\Model\Config;
use Couscous\Model\Repository;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Loads the Couscous config for the repository.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class LoadConfig implements StepInterface
{
    const FILENAME = 'couscous.yml';

    public function __invoke(Repository $repository, OutputInterface $output)
    {
        $filename = $repository->sourceDirectory . '/' . self::FILENAME;

        if (! isset($filename)) {
            $output->writeln("<comment>No couscous.yml configuration file found, using default config</comment>");

            // Default empty config
            $repository->config = new Config();
        }

        $repository->config = Config::fromYaml($filename);

        $repository->watchlist->watchFile($filename);
    }
}
