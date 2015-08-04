<?php

use App\Handlers\Events\CurrencyMessageReceived as CurrencyMessageReceivedHandler;
use \App\Events\CurrencyMessageReceived;
use \App\CurrencyMessage;

class CurrencyMessageReceivedHandlerTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $message = new CurrencyMessage();
        $message->currency_from = 'EUR';
        $message->currency_from = 'USD';
        $event = new CurrencyMessageReceived($message);
        $event->message = $message;
        $handler = new CurrencyMessageReceivedHandler();
        $handler->handle($event);
    }
}
