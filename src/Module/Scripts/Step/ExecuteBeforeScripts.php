<?php

namespace Couscous\Module\Scripts\Step;

use Couscous\Model\Project;
use Couscous\Step;

/**
 * Execute the scripts that were set in "scripts.before" in the configuration.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ExecuteBeforeScripts extends ExecuteScripts implements Step
{
    public function __invoke(Project $project)
    {
        $scripts = $project->metadata['scripts.before'];

        $this->executeScripts($scripts, $project);
    }
}
