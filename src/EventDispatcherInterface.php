<?php

declare(strict_types=1);

namespace Fi1a\EventDispatcher;

/**
 * Класс реализующий систему событий
 */
interface EventDispatcherInterface
{
    /**
     * Вызывает обработчики событий
     */
    public function dispatch(string $eventName, ?EventInterface $event = null): bool;

    /**
     * Вовзращает подписанные обработчики на событие
     *
     * @return array<string, array<array-key, callable>>
     */
    public function getListeners(string $eventName): array;

    /**
     * Есть ли подписанные обработчики событий
     */
    public function hasListeners(string $eventName): bool;

    /**
     * Вовзращает подписанные обработчики
     *
     * @return array<array-key, callable>
     */
    public function listListeners(): array;

    /**
     * Подписывает обработчик события на событие
     */
    public function addListener(string $eventName, callable $listener, int $priority = 2000): bool;

    /**
     * Удаляет подписанный обработчик события
     */
    public function removeListener(string $eventName, callable $listener): bool;

    /**
     * Добавляет события переданные классом подписок
     */
    public function addSubscriber(EventSubscriberInterface $subscriber): bool;

    /**
     * Удаляет события переданные классом подписок
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber): bool;
}
