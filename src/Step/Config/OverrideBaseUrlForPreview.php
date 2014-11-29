<?php

namespace Couscous\Step\Config;

use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Override the baseUrl if we are in preview.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class OverrideBaseUrlForPreview implements StepInterface
{
    public function __invoke(Repository $repository, OutputInterface $output)
    {
        if ($repository->metadata['preview'] === true) {
            $repository->metadata['baseUrl'] = '';
        }
    }
}
