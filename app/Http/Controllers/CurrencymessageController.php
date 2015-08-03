<?php

namespace App\Http\Controllers;

use App\CurrencyMessage;
use App\Events\CurrencyMessageReceived;
use App\MonthlyRate;
use Event;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use LRedis;

class CurrencymessageController extends Controller
{
    /**
     * Display the messages..
     *
     * @return Response
     */
    public function index()
    {
        // Select the rates of the year 2015.
        // NOTE: Just for the purpose of the exercise,
        // would be nicer to provide all the results available
        // grouped accordingly
        $rates = Db::table('monthly_rates')
            ->select('currency_from', 'currency_to', 'month', 'avg_rate')
            ->where('year', 2015)
            ->orderBy('currency_from')
            ->orderBy('currency_to')
            ->get();

        $messages = [];
        $prevCurFrom = null;
        $prevCurTo = null;
        $monthRates = [];
        $key = '';

        // Aggregate the messages as an array
        // currencyfrom_currencyto => [1 => avg_rate, 2 => ...]
        // where the inner array index corresponds to a month
        for ($i = 0; $i < count($rates); $i++)
        {
            $rate = $rates[$i];
            $monthRates[$rate->month] = $rate->avg_rate;

            if ($rate->currency_from != $prevCurFrom
                || $rate->currency_to != $prevCurTo)
            {
                $prevCurFrom = $rate->currency_from;
                $prevCurTo = $rate->currency_to;

                // Build the array key
                $key = $prevCurFrom . '-' . $prevCurTo;

                $messages[$key] = $monthRates;
                $monthRates = [];

            }

            if ($i == count($rates) - 1)
            {
                $key = $prevCurFrom . '-' . $prevCurTo;
                $messages[$key] = $messages[$key] + $monthRates;
            }
        }

        return view('socket', compact('messages'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request->isJson())
        {
            $validator = $this->getValidationFactory()->make($this->formatInput($request->all()), [
                'userId' => 'required',
                'currencyFrom' => 'required',
                'currencyTo' => 'required',
                'amountSell' => 'required',
                'amountBuy' => 'required',
                'rate' => 'required',
                'timePlaced' => 'required',
                'originatingCountry' => 'required'
            ]);

            if ($validator->fails()) {
                $response = response()->json($validator->errors()->getMessages(), 400);
            } else {
                DB::beginTransaction();

                try {
                    $message = new CurrencyMessage();
                    $request = (object)$request;
                    $message->user_id = $request->userId;
                    $message->currency_from = $request->currencyFrom;
                    $message->currency_to = $request->currencyTo;
                    $message->amount_sell = $request->amountSell;
                    $message->amount_buy = $request->amountBuy;
                    $message->rate = $request->rate;
                    $message->country_origin = $request->originatingCountry;
                    $message->time_placed = $request->timePlaced;

                    // Save all the messages, in case something is wrong and the
                    // statistics need to be recalculated
                    $message->save();

                    Event::fire(new CurrencyMessageReceived($message));

                    $response = response()->make(null, 201);

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    $response = response()->make($e->getMessage(), 500);
                }
            }

            return $response;
        } else {
            throw new BadRequestHttpException('This method accepts json requests.');
        }
    }

    // todo: make an helper
    protected function formatInput($request)
    {
        // Remove the empty spaces from the beginning and end of each param
        $request = array_map('trim', $request);

        return $request;
    }

//    public function test()
//    {
//        return view('test');
//    }
//
//    public function testEvent()
//    {
//        $podcast = CurrencyMessage::first();
//
//        // Purchase podcast logic...
//
//        Event::fire(new CurrencyMessageReceived($podcast));
//    }
}
