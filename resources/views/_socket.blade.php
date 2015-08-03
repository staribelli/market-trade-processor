@extends('layouts.master')

@section('content')
    {{--<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>--}}
    {{--<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>--}}
    {{--<script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>--}}
    {{--<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>--}}
    {{--<script type="text/javascript" src="/bower_components/Chart.js/Chart.js"></script>--}}
    {{--<link rel="stylesheet" href="/bower_components/angular-chart.js/dist/angular-chart.css">--}}
    {{--<script type="text/javascript" src="/bower_components/angular-chart.js/dist/angular-chart.js"></script>--}}


    {{--<script type="text/javascript" src="bower_components/ng-chartjs/dist/angular-chartjs.js"></script>--}}
    <div id="container" class="container">
        <div class="row" ng-controller="DataTablesCtrl">
            <div class="col-lg-6 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Chart Data</div>
                    <div class="panel-body">
                        <table class="table table-responsive table-condensed">
                            <tr>
                                <th ng-repeat="label in labels">{{label}}</th>
                            </tr>
                            <tr ng-repeat="dataSet in data">
                                <td ng-repeat="set in dataSet track by $index"><span style="text-align: right;">{{data[$parent.$index][$index]}}</span></td>
                            </tr>
                        </table>
                        <input ng-click="randomize()" value="Randomize" type="button" class="pull-right"/>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Reactive Chart</div>
                    <div class="panel-body">
                        <canvas id="tables" class="chart chart-line" data="data" labels="labels"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2" >
                <div id="messages" >
                    @if (!$messages->count())
                        No data
                    @else
                        <ul>
                            @foreach( $messages as $message )
                                <li>{{ $message->user_id }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        var socket = io.connect('http://localhost:8890');
        socket.on('message', function (data) {
            var message = $.parseJSON(data);
            var html = "<div style='border: thin solid green;'><p>"+message.currency_from+"</p>"
                    + "<p>"+message.currency_to+"</p>"
                    + "<p>"+message.avg_rate+"</p>"
                    + "<p>"+message.month+"</p>"
                    + "<p>"+message.year+"</p></div>"
            $( "#messages" ).html(html);
        });
////////////////////////////////////////
        angular.module("app", ["chart.js"])
            // Optional configuration
                .config(['ChartJsProvider', function (ChartJsProvider) {
                    // Configure all charts
                    ChartJsProvider.setOptions({
                        colours: ['#FF5252', '#FF8A80'],
                        responsive: false
                    });
                    // Configure all line charts
                    ChartJsProvider.setOptions('Line', {
                        datasetFill: false
                    });
                }])
                .controller("LineCtrl", ['$scope', '$timeout', function ($scope, $timeout) {

                    $scope.labels = ["January", "February", "March", "April", "May", "June", "July"];
                    $scope.series = ['Series A', 'Series B'];
                    $scope.data = [
                        [65, 59, 80, 81, 56, 55, 40],
                        [28, 48, 40, 19, 86, 27, 90]
                    ];
                    $scope.onClick = function (points, evt) {
                        console.log(points, evt);
                    };

                    // Simulate async data update
                    $timeout(function () {
                        $scope.data = [
                            [28, 48, 40, 19, 86, 27, 90],
                            [65, 59, 80, 81, 56, 55, 40]
                        ];
                    }, 3000);
                }]);
    </script>


@endsection