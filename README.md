# What is it
This is a simple real-time Market Trade processor using queues and websockets .

# How does it work
A POST request to the /messages url fires an App\Event\CurrencyMessageReceived event.
This event is handled by App\Handlers\Events\CurrencyMessageReceived which implementing the Laravel ShouldQueue
interface, instructs Laravel to put these handlers in a queue.

The queue used is the Laravel's default 'sync'.

The handler then processes the message creating a monthly average rate and pushes the message to Redis.
A Node.js server public/js/server.js subscribes to the Redis channel, waiting for messages and to channel them
to the frontend to be consumed by socket.io and rendered in a line graph.

# Tech Stack:
Laravel 5.1

PHP 5.6

Node.js

Express

MySql

Socket.io

Angular.js

Redis

## Tests
PHPUnit

## Hosting
Heroku

# Other info
## Login credentials for the POST request:
+ username: user@marketfair.com
+ password: processor

# API spec:

POST: /Messages

Auth: HTTP Basic

Headers: Content-type: application/json

Body request:
{"userId":"134256","currencyFrom":"EUR","currencyTo":"GBP","amountSell":1000,"amountBuy":747.10,"rate":0.7471,"timePlaced":"24-JAN-15 10:27:44","originatingCountry":"FR"}

Response: 201 Created / 401 unauthorized / 400 Bad request

## NOTE
It's important that the json does not contain any space or it won't be parsed. For example this is not a valid json:
```json
{"userId" : "134256"}
```

while this is a valid json:
```json
{"userId":"134256"}
```
# Known issues
Due to limitations on the hosting service, the node.js and Laravel app cannot coexist on the same server
and therefore they have two different urls.

Everything works firn in Chrome, setting the proper headers but Firefox blocks the requests due to Cross Site Requests.

The url though has to use the protocol http and not https or, for security reasons, it won't work.

The Node.js server is stored in public/js/server.js and
https://github.com/staribelli/market-trade-processor-node
