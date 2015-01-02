<?php

namespace Couscous;

use Couscous\Model\Repository;

/**
 * Generation step.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
interface Step
{
    /**
     * Process the given repository.
     *
     * @param Repository $repository
     */
    public function __invoke(Repository $repository);
}
