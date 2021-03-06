<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Integration\DecoratingMessages;

use EventSauce\EventSourcing\MessageDecoratorChain;
use EventSauce\EventSourcing\Message;
use PHPUnit\Framework\TestCase;

class MessageDecoratingTest extends TestCase
{
    /**
     * @test
     */
    public function decorating_messages()
    {
        $decorator = new MessageDecoratorChain(new DummyMessageDecorator());
        $event = new DummyDecoratedEvent();
        $message = new Message($event);
        $decoratedMessage = $decorator->decorate($message);
        $this->assertEquals($event, $decoratedMessage->event());
        $this->assertEquals('value', $decoratedMessage->header('dummy'));
    }
}
