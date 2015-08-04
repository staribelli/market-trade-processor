<?php

class CurrencyMessageControllerTest extends TestCase
{
    public function testStore_unauthorized()
    {
        $this->post('/messages')
            ->seeStatusCode(401);
    }

    public function testStore_bad_request()
    {
        // Disable the auth
        $this->withoutMiddleware();
        $this->post('/messages')
                ->seeStatusCode(400);
    }

    public function testStore_fails_validation()
    {
        $this->withoutMiddleware();
        $this->call('POST', '/messages', [], [], [], ['CONTENT_TYPE' => 'application/json']);
        $this->seeStatusCode(400)
            ->seeJsonEquals([
                "amountBuy" => ["The amount buy field is required."],
                "amountSell" => ["The amount sell field is required."],
                "currencyFrom" => ["The currency from field is required."],
                "currencyTo" => ["The currency to field is required."],
                "originatingCountry" => ["The originating country field is required."],
                "rate" => ["The rate field is required."],
                "timePlaced" => ["The time placed field is required."],
                "userId" => ["The user id field is required."]
            ]);
    }

    public function testStore_ok()
    {
        $this->markTestIncomplete("Laravel's function is not sending the proper headers and data and the curl request does not set the app as testing");
        $this->withoutMiddleware();

        $data = [
            "amountBuy" => 100,
            "amountSell" => 200,
            "currencyFrom" => 'EUR',
            "currencyTo" => 'GBP',
            "originatingCountry" => 'FR',
            "rate" => 0.7,
            "timePlaced" => date('Y-m-d'),
            "userId" => 1
        ];

        // Laravel's function is not sending the proper headers and data. make a curl request instead
        $curl = curl_init();
        curl_setopt_array(
            $curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL            => $this->baseUrl . '/index.php/messages',
                CURLOPT_POST           => 1,
                CURLOPT_POSTFIELDS     => json_encode($data),
                CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
                CURLOPT_USERPWD        => 'user@marketfair.com:processor'
            )
        );

        curl_exec($curl);
        $requestInfo = curl_getinfo($curl);
        curl_close($curl);

        $this->assertEquals(201, $requestInfo['http_code']);
    }
}
