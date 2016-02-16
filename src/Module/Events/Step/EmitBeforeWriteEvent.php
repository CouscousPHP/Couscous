<?php

namespace Couscous\Module\Events\Step;

use Couscous\Event\EventManager;
use Couscous\Model\Project;
use Couscous\Step;

/**
 * Fire the event couscous.step.before_write
 *
 * @author Bob Mulder <bobmulder@outlook.com>
 */
class EmitBeforeWriteEvent implements Step
{
    public function __invoke(Project $project)
    {
        $emitter = EventManager::emitter();
        $emitter->emit('couscous.step.before_write', ['project' => $project]);
    }
}
