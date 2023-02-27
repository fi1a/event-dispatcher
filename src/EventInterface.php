<?php

declare(strict_types=1);

namespace Fi1a\EventDispatcher;

/**
 * Интерфейс события
 */
interface EventInterface
{
    /**
     * Был ли остановлен вызов этого события
     */
    public function isPropagationStopped(): bool;

    /**
     * Остановить распространение этого события
     */
    public function stopPropagation(): bool;
}
