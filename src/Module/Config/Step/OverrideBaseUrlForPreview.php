<?php

namespace Couscous\Module\Config\Step;

use Couscous\Model\Repository;

/**
 * Override the baseUrl if we are in preview.
 *
 * @author Matthieu Napoli <matthieu@mnapoli.fr>
 */
class OverrideBaseUrlForPreview implements \Couscous\Step
{
    public function __invoke(Repository $repository)
    {
        if ($repository->metadata['preview'] === true) {
            $repository->metadata['baseUrl'] = '';
        }
    }
}
