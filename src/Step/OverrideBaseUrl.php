<?php

namespace Couscous\Step;

use Couscous\Model\Repository;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Override the baseUrl template variable in the config.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class OverrideBaseUrl implements StepInterface
{
    public function __invoke(Repository $repository, OutputInterface $output)
    {
        if (! isset($repository->overrideBaseUrl)) {
            return;
        }

        $repository->config->templateVariables['baseUrl'] = $repository->overrideBaseUrl;
    }
}
