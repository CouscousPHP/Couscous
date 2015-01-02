<?php

namespace Couscous\Module\Config\Step;

use Couscous\Model\Repository;
use Couscous\Step;

/**
 * Set the default config.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class SetDefaultConfig implements Step
{
    private $defaultConfig = [
        'exclude' => [
            'vendor', 'website'
        ],
    ];

    public function __invoke(Repository $repository)
    {
        $repository->metadata->setMany($this->defaultConfig);
    }
}
