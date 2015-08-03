<?php

namespace App\Events;

use App\CurrencyMessage;
use App\Podcast;
use Illuminate\Queue\SerializesModels;

class CurrencyMessageReceived extends Event
{
    use SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @param  CurrencyMessage  $message
     * @return void
     */
    public function __construct(CurrencyMessage $message)
    {
        $this->message = $message;
    }
}