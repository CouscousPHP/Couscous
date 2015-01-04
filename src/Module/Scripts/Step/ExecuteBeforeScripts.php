<?php

namespace Couscous\Module\Scripts\Step;

use Couscous\Model\Repository;
use Couscous\Step;

/**
 * Execute the scripts that were set in "scripts.before" in the configuration.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class ExecuteBeforeScripts extends ExecuteScripts implements Step
{
    public function __invoke(Repository $repository)
    {
        $scripts = $repository->metadata['scripts.before'];

        $this->executeScripts($scripts, $repository);
    }
}
