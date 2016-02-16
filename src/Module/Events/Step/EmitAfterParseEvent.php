<?php

namespace Couscous\Module\Events\Step;

use Couscous\Event\EventManager;
use Couscous\Model\Project;
use Couscous\Step;

/**
 * Fire the event couscous.step.after_parse.
 *
 * @author Bob Mulder <bobmulder@outlook.com>
 */
class EmitAfterParseEvent implements Step
{
    public function __invoke(Project $project)
    {
        $emitter = EventManager::emitter();
        $emitter->emit('couscous.step.after_parse', ['project' => $project]);
    }
}
