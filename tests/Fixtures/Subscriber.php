<?php

declare(strict_types=1);

namespace Fi1a\Unit\EventDispatcher\Fixtures;

use Fi1a\EventDispatcher\EventInterface;
use Fi1a\EventDispatcher\EventSubscriberInterface;

/**
 * Реализует подписки на события
 */
class Subscriber implements EventSubscriberInterface
{
    /**
     * Обработчик события
     */
    public function fooListener(EventInterface $event): bool
    {
        return true;
    }

    /**
     * Обработчик события
     */
    public function barListener(EventInterface $event): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getListeners(): array
    {
        return [
            'onBeforeFoo' => [
                'listener' => 'fooListener',
            ],
            'onBeforeBar' => [
                [
                    'listener' => 'barListener',
                ],
            ],
        ];
    }
}
