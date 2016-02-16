<?php

namespace Couscous\Event\Listener;

use Couscous\Model\Project;
use Couscous\Step;
use League\Event\EventInterface;

/**
 * After Render Markdown Event Listener
 *
 * @author Bob Mulder <bobmulder@outlook.com>
 */
class AfterRenderMarkdownListener extends BaseListener
{

    /**
     * Handle the event.
     *
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        $project = func_get_arg(1)['project'];

        var_dump('after_render_markdown');
    }
}
