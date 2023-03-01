# Позволяет компонентам взаимодействовать друг с другом с помощью событий

[![Latest Version][badge-release]][packagist]
[![Software License][badge-license]][license]
[![PHP Version][badge-php]][php]
![Coverage Status][badge-coverage]
[![Total Downloads][badge-downloads]][downloads]
[![Support mail][badge-mail]][mail]

## Установка

Установить этот пакет можно как зависимость, используя Composer.

``` bash
composer require fi1a/event-dispatcher
```

## Подписываемся на события

Подписаться на событие можно с помощью метода `addListener` класса `Fi1a\EventDispatcher\EventDispatcher`,
передав название события, обработчик и приоритет (чем приоритет меньше, тем раньше обработчик будет вызван по отношению
к дугим обработчикам).

```php
use Fi1a\EventDispatcher\EventDispatcher;
use Fi1a\EventDispatcher\EventInterface;

$dispatcher = new EventDispatcher();

$dispatcher->addListener('onBeforeFoo', function (EventInterface $event): bool {
    return true;
}, 5000);
```

Или через класс подписок с помощью метода `addSubscriber` класса `Fi1a\EventDispatcher\EventDispatcher`

```php
use Fi1a\EventDispatcher\EventInterface;
use Fi1a\EventDispatcher\EventDispatcher;
use Fi1a\EventDispatcher\EventSubscriberInterface;

class FooSubscriber implements EventSubscriberInterface
{
    public function onBeforeFooHandler(EventInterface $event): bool
    {
        return true;
    }

    public function getListeners(): array
    {
        return [
            'onBeforeFoo' => [
                [
                    'listener' => 'onBeforeFooHandler',
                    'priority' => 5000,
                ],
            ],
        ];
    }

}

$dispatcher = new EventDispatcher();

$dispatcher->addSubscriber(new FooSubscriber());
```

## Вызов обработчиков событий

Вызов зарегистрированных обработчиков событий осуществляется методом `dispatch`,
класса `Fi1a\EventDispatcher\EventDispatcher`.

```php
use Fi1a\EventDispatcher\Event;
use Fi1a\EventDispatcher\EventDispatcher;

$dispatcher = new EventDispatcher();

$event = new Event();
$dispatcher->dispatch('onBeforeFoo', $event);
```

## Остановить вызов событий

С помощью метода `stopPropagation` можно остановить вызов событий. Ниже в примере, второй обработчик события
`onBeforeFoo` не будет вызван, так как вызов событий был остановлен в первом обработчике методом `stopPropagation` класса
`Fi1a\EventDispatcher\EventInterface`.

```php
use Fi1a\EventDispatcher\EventDispatcher;
use Fi1a\EventDispatcher\EventInterface;

$dispatcher = new EventDispatcher();

$dispatcher->addListener('onBeforeFoo', function (EventInterface $event): bool {
    echo 'onBeforeFoo 1';
    $event->stopPropagation();

    return true;
});

$dispatcher->addListener('onBeforeFoo', function (EventInterface $event): bool {
    echo 'onBeforeFoo 2';

    return true;
});

$dispatcher->dispatch('onBeforeFoo'); // onBeforeFoo 1
```

[badge-release]: https://img.shields.io/packagist/v/fi1a/event-dispatcher?label=release
[badge-license]: https://img.shields.io/github/license/fi1a/event-dispatcher?style=flat-square
[badge-php]: https://img.shields.io/packagist/php-v/fi1a/event-dispatcher?style=flat-square
[badge-coverage]: https://img.shields.io/badge/coverage-100%25-green
[badge-downloads]: https://img.shields.io/packagist/dt/fi1a/event-dispatcher.svg?style=flat-square&colorB=mediumvioletred
[badge-mail]: https://img.shields.io/badge/mail-support%40fi1a.ru-brightgreen

[packagist]: https://packagist.org/packages/fi1a/event-dispatcher
[license]: https://github.com/fi1a/event-dispatcher/blob/master/LICENSE
[php]: https://php.net
[downloads]: https://packagist.org/packages/fi1a/event-dispatcher
[mail]: mailto:support@fi1a.ru