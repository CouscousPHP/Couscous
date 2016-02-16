<?php

namespace Couscous\Module\Events\Step;

use Couscous\Event\EventManager;
use Couscous\Model\Project;
use Couscous\Step;

/**
 * Fire the event couscous.step.after_config.
 *
 * @author Bob Mulder <bobmulder@outlook.com>
 */
class EmitAfterConfigEvent implements Step
{
    public function __invoke(Project $project)
    {
        $emitter = EventManager::emitter();
        $emitter->emit('couscous.step.after_config', ['project' => $project]);
    }
}
