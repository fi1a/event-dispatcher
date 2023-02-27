<?php

declare(strict_types=1);

namespace Fi1a\Unit\EventDispatcher;

use Fi1a\EventDispatcher\Event;
use PHPUnit\Framework\TestCase;

/**
 * Интерфейс события
 */
class EventTest extends TestCase
{
    /**
     * Остановить распространение этого события
     */
    public function testStopPropagation(): void
    {
        $event = new Event();
        $this->assertFalse($event->isPropagationStopped());
        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }
}
