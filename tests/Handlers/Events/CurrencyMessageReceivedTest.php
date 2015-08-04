<?php

use \App\Events\CurrencyMessageReceived;
use \App\CurrencyMessage;

class CurrencyMessageReceivedTest extends TestCase
{
    public function testHandle()
    {
        $message = new CurrencyMessage();
        $message->currency_from = 'EUR';
        $message->currency_to = 'USD';
        $message->rate = 1.1;

        $event = new CurrencyMessageReceived($message);
        $event->message = $message;

        $handlerMock = $this->getMock('App\Handlers\Events\CurrencyMessageReceived', ['pushToSocket']);
        $handlerMock->expects($this->once())
            ->method('pushToSocket');
        $handlerMock->handle($event);
    }
}
