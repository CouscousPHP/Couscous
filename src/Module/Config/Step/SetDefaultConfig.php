<?php

namespace Couscous\Module\Config\Step;

use Couscous\Model\Repository;
use Couscous\Step\StepInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Set the default config.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class SetDefaultConfig implements StepInterface
{
    private $defaultConfig = [
        'exclude' => [
            'vendor', 'website'
        ],
    ];

    public function __invoke(Repository $repository, OutputInterface $output)
    {
        $repository->metadata->setMany($this->defaultConfig);
    }
}
