@extends('layouts.master')

@section('content')
    <p id="power">0</p>
@stop

@section('scripts')

    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>

    <script>
        var socket = io('http://localhost:8890');
        socket.on("test-channel:App\\Events\\MyEventNameHere", function(message){
            // increase the power everytime we load test route
            $('#power').text(parseInt($('#power').text()) + parseInt(message.data.power));
        });
    </script>


@stop