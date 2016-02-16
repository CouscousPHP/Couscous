<?php

namespace Couscous\Event\Listener;

use Couscous\Model\Project;
use Couscous\Step;
use League\Event\EventInterface;

/**
 * After Parse Event Listener
 *
 * @author Bob Mulder <bobmulder@outlook.com>
 */
class AfterParseListener extends BaseListener
{

    /**
     * Handle the event.
     *
     * @param EventInterface $event
     */
    public function handle(EventInterface $event)
    {
        $project = func_get_arg(1)['project'];

        var_dump('after_parse');
    }
}
