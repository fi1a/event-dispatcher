<?php

declare(strict_types=1);

namespace Fi1a\EventDispatcher;

/**
 * Реализует подписки на события
 */
interface EventSubscriberInterface
{
    /**
     * Возвращает события для подписки
     *
     * @return array<string, array{
     *     listener: string,
     *     priority: int|null
     * }>|array<string, array<array-key, array{
     *     listener: string,
     *     priority: int|null
     * }>>
     */
    public function getListeners(): array;
}
