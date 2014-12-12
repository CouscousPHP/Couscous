<?php

namespace Couscous\Module\Config\Step;

use Couscous\Model\Repository;
use Couscous\Step;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Override the baseUrl if we are in preview.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class OverrideBaseUrlForPreview implements \Couscous\Step
{
    public function __invoke(Repository $repository, OutputInterface $output)
    {
        if ($repository->metadata['preview'] === true) {
            $repository->metadata['baseUrl'] = '';
        }
    }
}
