<?php

declare(strict_types=1);

namespace Fi1a\EventDispatcher;

/**
 * Интерфейс события
 */
class Event implements EventInterface
{
    /**
     * @var bool
     */
    protected $stopPropagation = false;

    /**
     * @inheritDoc
     */
    public function isPropagationStopped(): bool
    {
        return $this->stopPropagation;
    }

    /**
     * @inheritDoc
     */
    public function stopPropagation(): bool
    {
        $this->stopPropagation = true;

        return true;
    }
}
