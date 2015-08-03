<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Handlers\Events\CurrencyMessageReceivedHandler;
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
