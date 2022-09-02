<?php
declare(strict_types = 1);

namespace Couscous\Module\Config\Step;

use Couscous\Model\Project;
use Couscous\Step;

/**
 * Override the baseUrl if we are in preview.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class OverrideBaseUrlForPreview implements Step
{
    public function __invoke(Project $project): void
    {
        if ($project->metadata['preview'] === true) {
            $project->metadata['baseUrl'] = '';
        }
    }
}
