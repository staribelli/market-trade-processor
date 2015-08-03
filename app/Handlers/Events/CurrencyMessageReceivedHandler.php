<?php

namespace App\Handlers\Events;

use App\Events\CurrencyMessageReceived;
use App\MonthlyRate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use LRedis;

class CurrencyMessageReceivedHandler implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param CurrencyMessageReceived
     * @return void
     */
    public function handle(CurrencyMessageReceived $event)
    {
        $timePlaced = new \DateTime($event->message->time_placed);
        $rate = MonthlyRate::where('year', $timePlaced->format('Y'))
            ->where('month', $timePlaced->format('m'))
            ->where('currency_from', $event->message->getAttribute('currency_from'))
            ->where('currency_to', $event->message->getAttribute('currency_to'))
            ->get()
            ->first();

        if (is_null($rate)) {
            $rate = new MonthlyRate();
            $rate->tot_messages = 1;
            $rate->currency_from = $event->message->getAttribute('currency_from');
            $rate->currency_to = $event->message->getAttribute('currency_to');
            $rate->sum_rate = $event->message->getAttribute('rate');
            $rate->month = $timePlaced->format('m');
            $rate->year = $timePlaced->format('Y');
        } else {
            $rate->tot_messages++;
            $rate->sum_rate += $event->message->getAttribute('rate');
        }

        DB::beginTransaction();
        try {
            $rate->save();

            $key = $rate->currency_from . '-' . $rate->currency_to;
            $message = [$key => [
                'rate' => $rate->avg_rate,
                'month' => $rate->month
                ]
            ];
            // Push message in redis to be displayed in the frontend
            $redis = LRedis::connection();
            $redis->publish('message', json_encode($message));

            DB::commit();
        } catch (\ErrorException $e) {
            DB::rollBack();

            // Throw the exception again to signal an error
            // in the processing
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}