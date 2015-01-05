<?php

namespace Couscous\Module\Scripts\Step;

use Couscous\Model\Project;
use Couscous\Step;

/**
 * Execute the scripts that were set in "scripts.after" in the configuration.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ExecuteAfterScripts extends ExecuteScripts implements Step
{
    public function __invoke(Project $project)
    {
        $scripts = $project->metadata['scripts.after'];

        $this->executeScripts($scripts, $project);
    }
}
