<?php

namespace Couscous\Event\Listener;

use Couscous\Model\Project;
use Couscous\Step;
use League\Event\EventInterface;

/**
 * Before Render Markdown Event Listener
 *
 * @author Bob Mulder <bobmulder@outlook.com>
 */
class BeforeRenderMarkdownListener extends BaseListener
{

    /**
     * Handle the event.
     *
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        $project = func_get_arg(1)['project'];

        var_dump('before_render_markdown');
    }
}
