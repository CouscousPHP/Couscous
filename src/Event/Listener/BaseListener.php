<?php

namespace Couscous\Event\Listener;

use DI\Container;
use DI\ContainerBuilder;
use League\Event\EventInterface;
use League\Event\ListenerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Base Event Listener
 *
 * @author Bob Mulder <bobmulder@outlook.com>
 */
class BaseListener implements ListenerInterface
{

    /**
     * Handle the event.
     *
     * @param EventInterface $event
     *
     * @return void
     */
    public function handle(EventInterface $event)
    {
    }

    /**
     * Check whether the listener is the given parameter.
     *
     * @param mixed $listener
     *
     * @return bool
     */
    public function isListener($listener)
    {
        return $listener === $this;
    }
}
