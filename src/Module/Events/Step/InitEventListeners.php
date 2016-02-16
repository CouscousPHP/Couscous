<?php

namespace Couscous\Module\Events\Step;

use Couscous\Event\EventManager;
use Couscous\Model\Project;
use Couscous\Step;

/**
 * Initializes all listeners from the config to the emitter.
 *
 * @author Bob Mulder <bobmulder@outlook.com>
 */
class InitEventListeners implements Step
{
    public function __invoke(Project $project)
    {
        $emitter = EventManager::emitter();

        $listeners = (array)$project->metadata['events.listeners'];

        foreach ($listeners as $listener) {
            $emitter->addListener(
                $listener['event'],
                new $listener['listener'],
                array_key_exists('priority', $listener) ? $listener['priority'] : null
            );
        }
    }
}
