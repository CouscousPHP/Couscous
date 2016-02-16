<?php

namespace Couscous\Event;

use DI\Container;
use DI\ContainerBuilder;
use League\Event\Emitter;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Eventmanager.
 * Handles the event emitter.
 *
 * @author Bob Mulder <bobmulder@outlook.com>
 */
class EventManager
{
    /**
     * The emitter instance.
     *
     * @var Emitter
     */
    protected static $emitter;

    /**
     * Returns the static instance of the emitter.
     *
     * @return Emitter
     */
    public static function &emitter()
    {
        if(!self::$emitter) {
            self::$emitter = new Emitter();
        }
        return self::$emitter;
    }
}
