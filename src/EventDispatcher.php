<?php

declare(strict_types=1);

namespace Fi1a\EventDispatcher;

use Closure;

/**
 * Реализует систему событий
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var array<string, array<int, array<array-key, callable>>>
     */
    private $listeners = [];

    /**
     * @inheritDoc
     */
    public function dispatch(string $eventName, ?EventInterface $event = null): bool
    {
        if (!$this->hasListeners($eventName)) {
            return true;
        }

        if (is_null($event)) {
            $event = new Event();
        }

        $listeners = $this->getListeners($eventName);
        $dispatch = true;
        foreach ($listeners as $listener) {
            $dispatch = ($listener instanceof Closure
                    ? $listener($event)
                    : call_user_func($listener, $event))
                && $dispatch;

            if ($event->isPropagationStopped()) {
                break;
            }
        }

        return $dispatch;
    }

    /**
     * @inheritDoc
     */
    public function getListeners(string $eventName): array
    {
        if (!$this->hasListeners($eventName)) {
            return [];
        }

        $listeners = [];
        foreach ($this->listeners[$eventName] as $items) {
            foreach ($items as $listener) {
                $listeners[] = $listener;
            }
        }

        return $listeners;
    }

    /**
     * @inheritDoc
     */
    public function hasListeners(string $eventName): bool
    {
        return array_key_exists($eventName, $this->listeners);
    }

    /**
     * @inheritDoc
     */
    public function listListeners(): array
    {
        return $this->listeners;
    }

    /**
     * @inheritDoc
     */
    public function addListener(string $eventName, callable $listener, ?int $priority = null): bool
    {
        if ($priority === null) {
            $priority = 2000;
        }
        $this->listeners[$eventName][$priority][] = $listener;
        ksort($this->listeners[$eventName]);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function removeListener(string $eventName, callable $listener): bool
    {
        if (!$this->hasListeners($eventName)) {
            return false;
        }

        $remove = false;
        foreach ($this->listeners[$eventName] as $priority => $listeners) {
            foreach ($listeners as $index => $item) {
                if ($listener === $item) {
                    unset($this->listeners[$eventName][$priority][$index]);
                    if (!count($this->listeners[$eventName][$priority])) {
                        unset($this->listeners[$eventName][$priority]);
                    }
                    if (!count($this->listeners[$eventName])) {
                        unset($this->listeners[$eventName]);
                    }
                    $remove = true;
                }
            }
        }

        return $remove;
    }

    /**
     * @inheritDoc
     */
    public function addSubscriber(EventSubscriberInterface $subscriber): bool
    {
        foreach ($subscriber->getListeners() as $eventName => $params) {
            if (isset($params['listener'])) {
                $params = [$params];
            }
            foreach ($params as $param) {
                if (isset($param['listener'])) {
                    $this->addListener(
                        $eventName,
                        [$subscriber, $param['listener']],
                        ($param['priority'] ?? null)
                    );
                }
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber): bool
    {
        foreach ($subscriber->getListeners() as $eventName => $params) {
            if (isset($params['listener'])) {
                $params = [$params];
            }
            foreach ($params as $param) {
                if (isset($param['listener'])) {
                    $this->removeListener($eventName, [$subscriber, $param['listener']]);
                }
            }
        }

        return true;
    }
}
