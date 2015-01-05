<?php

namespace Couscous;

use Couscous\Model\Project;

/**
 * Generation step.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface Step
{
    /**
     * Process the given project.
     *
     * @param Project $project
     */
    public function __invoke(Project $project);
}
