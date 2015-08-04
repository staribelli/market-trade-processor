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
