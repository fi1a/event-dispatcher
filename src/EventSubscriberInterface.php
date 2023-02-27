<?php

declare(strict_types=1);

namespace Fi1a\EventDispatcher;

/**
 * Интерфейс реализующий подписки на события
 */
interface EventSubscriberInterface
{
    /**
     * Возвращает события для подписки
     *
     * @return array<string, array{listener: string, priority: int|null}>
     */
    public function getListeners(): array;
}
