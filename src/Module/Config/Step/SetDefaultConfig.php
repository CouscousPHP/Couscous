<?php
declare(strict_types = 1);

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
    /**
     * @var array
     */
    private $defaultConfig = [
        'exclude' => [
            'vendor', 'website',
        ],
    ];

    public function __invoke(Project $project): void
    {
        $project->metadata->setMany($this->defaultConfig);
    }
}
