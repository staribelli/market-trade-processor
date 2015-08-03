<!DOCTYPE html>
<html>
<head>
    <title>MarketFair - @yield('title')</title>

    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">--}}
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">--}}
    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>--}}

    {{--<!-- Our WebRTC application styling -->--}}
    {{--<link rel="stylesheet" type="text/css" href="style/datachannel-demo.css">--}}

    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>

    <script type="text/javascript" src="/bower_components/angular-chart.js/dist/angular-chart.js"></script>
    <link rel="stylesheet" href="/bower_components/angular-chart.js/dist/angular-chart.css">

    <script src="../bower_components/angular/angular.min.js"></script>
    <script src="../bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
    <script src="../bower_components/Chart.js/Chart.min.js"></script>
    {{--<script src="../angular-chart.js"></script>--}}
    <script src="/js/app.js"></script>

    {{--<script type="text/javascript" src="/bower_components/ng-chartjs/dist/angular-chartjs.js"></script>--}}
</head>
<body ng-app="examples">
    @section('sidebar')
    @show

    <div class="container">
        @yield('content')
    </div>

    <!-- Zepto for AJAX -->
    {{--<script src="//cdnjs.cloudflare.com/ajax/libs/zepto/1.1.3/zepto.min.js"></script>--}}

    <!-- Pusher for WebRTC signalling -->
    {{--<script src="//js.pusher.com/2.2/pusher.js"></script>--}}

    <!-- DataChannel.js for WebRTC functionality -->
    {{--<script src="//webrtc-experiment.com/DataChannel.js"></script>--}}

    <!-- Our WebRTC application -->
    {{--<script src="js/datachannel-demo.js"></script>--}}
</body>
</html>