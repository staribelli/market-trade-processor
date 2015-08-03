<?php
$start = microtime(true);
$curl = curl_init();

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array(
    $curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL            => 'http://markettradeprocessor/index.php/messages',
        CURLOPT_POST           => 1,
        CURLOPT_POSTFIELDS     => '{"userId":"134256","currencyFrom":"EUR","currencyTo":"GBP","amountSell":1000,"amountBuy":747.10,"rate":0.7471,"timePlaced":"24-JAN-15 10:27:44","originatingCountry":"FR"}',
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json']
    )
);

for ($i = 0; $i  < 1000; $i++) {
    echo $i . "\n";

// Send the request & save response to $resp
    $resp = curl_exec($curl);

}

// Close request to clear up some resources
curl_close($curl);

$end = microtime(true);
echo $end - $start . "\n";
