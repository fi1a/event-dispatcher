<?php

declare(strict_types=1);

namespace Fi1a\Unit\EventDispatcher\Fixtures;

use Fi1a\EventDispatcher\Event as DispatcherEvent;

/**
 * Событие
 */
class Event extends DispatcherEvent
{
    public $foo;
}
