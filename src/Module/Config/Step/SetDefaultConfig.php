<?php

namespace Couscous\Module\Config\Step;

use Couscous\Model\Project;
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
            'vendor', 'website',
        ],
    ];

    public function __invoke(Project $project)
    {
        $project->metadata->setMany($this->defaultConfig);
    }
}
