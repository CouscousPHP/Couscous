<?php
declare(strict_types = 1);

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
     */
    public function __invoke(Project $project): void;
}
