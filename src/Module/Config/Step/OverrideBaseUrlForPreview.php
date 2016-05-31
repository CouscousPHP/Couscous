<?php

namespace Couscous\Module\Config\Step;

use Couscous\Model\Project;

/**
 * Override the baseUrl if we are in preview.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class OverrideBaseUrlForPreview implements \Couscous\Step
{
    public function __invoke(Project $project)
    {
        if ($project->metadata['preview'] === true) {
            $project->metadata['baseUrl'] = '';
        }
    }
}
