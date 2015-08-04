<?php

namespace App\Http\Controllers;

use App\CurrencyMessage;
use App\Events\CurrencyMessageReceived;
use Event;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use LRedis;

class CurrencymessageController extends Controller
{
    /**
     * Init the index view with the current
     * average rate data stored in the db.
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

        // Initialize the messages array as charts.js expects it
        if (empty($rates))
        {
            $messages = [[]];
        } else {
            $messages = [];
        }

        $monthRates = [];
        $totRates = count($rates);

        // Aggregate the messages as an array
        // currencyfrom_currencyto => [1 => avg_rate, 2 => ...]
        // where the inner array index corresponds to a month
        for ($i = 0; $i < $totRates; $i++) {
            $rate = $rates[$i];
            $monthRates[$rate->month] = $rate->avg_rate;

            if ($i == $totRates - 1) {
                $key = $rate->currency_from . '-' . $rate->currency_to;
                $messages[$key] = $monthRates;
            }
            elseif ($rate->currency_from != $rates[$i + 1]->currency_from
                    || $rate->currency_to != $rates[$i + 1]->currency_to)
            {
                        // Build the array key and store the data
                        $key = $rate->currency_from . '-' . $rate->currency_to;
                        $messages[$key] = $monthRates;
                        $monthRates = [];
            }
        }

        return view('socket', compact('messages'));
    }

    /**
     * Store the message received from the api call
     * POST /messages
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request->isJson())
        {
            $input = $this->formatInput($request->all());
            $validator = $this->getValidationFactory()->make($input, [
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
                    $request = (object)$input;
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

    /**
     * Removes trailing and ending spaces from
     * each input value;
     *
     * @param $request
     * @return array
     */
    protected function formatInput($request)
    {
        $request = array_map('trim', $request);

        return $request;
    }

}
