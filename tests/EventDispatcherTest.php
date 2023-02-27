<?php

declare(strict_types=1);

namespace Fi1a\Unit\EventDispatcher;

use Fi1a\EventDispatcher\EventDispatcher;
use Fi1a\EventDispatcher\EventDispatcherInterface;
use Fi1a\EventDispatcher\EventSubscriberInterface;
use Fi1a\Unit\EventDispatcher\Fixtures\Event;
use Fi1a\Unit\EventDispatcher\Fixtures\Subscriber;
use PHPUnit\Framework\TestCase;

/**
 * Реализует систему событий
 */
class EventDispatcherTest extends TestCase
{
    /**
     * @var EventDispatcherInterface
     */
    protected static $eventDispatcher;

    /**
     * @var callable
     */
    protected static $listener1;

    /**
     * @var callable
     */
    protected static $listener2;

    /**
     * @var EventSubscriberInterface
     */
    protected static $subscriber;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$eventDispatcher = new EventDispatcher();
        static::$listener1 = function (Event $event): bool {
            $event->foo = true;
            $event->stopPropagation();

            return true;
        };
        static::$listener2 = function (Event $event): bool {
            $event->foo = false;

            return true;
        };
        static::$subscriber = new Subscriber();
    }

    /**
     * Подписывает обработчик события на событие
     */
    public function testAddListener(): void
    {
        $this->assertFalse(static::$eventDispatcher->hasListeners('onBeforeFoo'));
        static::$eventDispatcher->addListener('onBeforeFoo', static::$listener1);
        static::$eventDispatcher->addListener('onBeforeFoo', static::$listener2);
        $this->assertTrue(static::$eventDispatcher->hasListeners('onBeforeFoo'));
    }

    /**
     * Вызывает обработчики событий
     *
     * @depends testAddListener
     */
    public function testDispatch(): void
    {
        $this->assertTrue(static::$eventDispatcher->dispatch('unknown'));
        $event = new Event();
        $this->assertTrue(static::$eventDispatcher->dispatch('onBeforeFoo', $event));
        $this->assertTrue($event->foo);
    }

    /**
     * Вовзращает подписанные обработчики на событие
     *
     * @depends testAddListener
     */
    public function testGetListeners(): void
    {
        $this->assertCount(0, static::$eventDispatcher->getListeners('unknown'));
        $this->assertCount(2, static::$eventDispatcher->getListeners('onBeforeFoo'));
    }

    /**
     * Вовзращает подписанные обработчики
     *
     * @depends testAddListener
     */
    public function testListListeners(): void
    {
        $this->assertCount(1, static::$eventDispatcher->listListeners());
    }

    /**
     * Удаляет подписанный обработчик события
     *
     * @depends testAddListener
     */
    public function testRemoveListeners(): void
    {
        $this->assertTrue(static::$eventDispatcher->hasListeners('onBeforeFoo'));
        $this->assertFalse(static::$eventDispatcher->removeListener('unknown', static::$listener2));
        $this->assertTrue(static::$eventDispatcher->removeListener('onBeforeFoo', static::$listener1));
        $this->assertTrue(static::$eventDispatcher->removeListener('onBeforeFoo', static::$listener2));
        $this->assertFalse(static::$eventDispatcher->removeListener('onBeforeFoo', static::$listener2));
        $this->assertFalse(static::$eventDispatcher->hasListeners('onBeforeFoo'));
    }

    /**
     * Добавляет события переданные классом подписок
     */
    public function testAddSubscriber(): void
    {
        $this->assertFalse(static::$eventDispatcher->hasListeners('onBeforeBar'));
        static::$eventDispatcher->addSubscriber(static::$subscriber);
        $this->assertTrue(static::$eventDispatcher->hasListeners('onBeforeBar'));
    }

    /**
     * Вызывает обработчики событий
     *
     * @depends testAddSubscriber
     */
    public function testDispatchDefaultEvent(): void
    {
        $this->assertTrue(static::$eventDispatcher->dispatch('onBeforeBar'));
    }

    /**
     * Удаляет события переданные классом подписок
     *
     * @depends testAddSubscriber
     */
    public function testRemoveSubscriber(): void
    {
        $this->assertTrue(static::$eventDispatcher->hasListeners('onBeforeBar'));
        static::$eventDispatcher->removeSubscriber(static::$subscriber);
        $this->assertFalse(static::$eventDispatcher->hasListeners('onBeforeBar'));
    }
}
