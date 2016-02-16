<?php

namespace Couscous\Module\Events\Step;

use Couscous\Event\EventManager;
use Couscous\Model\Project;
use Couscous\Step;

/**
 * Fire the event couscous.step.before_render_markdown
 *
 * @author Bob Mulder <bobmulder@outlook.com>
 */
class EmitBeforeRenderMarkdownEvent implements Step
{
    public function __invoke(Project $project)
    {
        $emitter = EventManager::emitter();
        $emitter->emit('couscous.step.before_render_markdown', ['project' => $project]);
    }
}
