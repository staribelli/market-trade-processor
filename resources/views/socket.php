<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <title>Currency rate graph</title>
        <link rel="stylesheet"
              href="/bower_components/angular-chart.js/dist/angular-chart.css">
        <link href="/bower_components/angular-chart.js/examples/bootstrap.css"
              rel="stylesheet">
    </head>
    <body ng-app="currencyGraph">
        <div class="container"></div>
        <div id="container"
             class="container">
            <div class="row" ng-controller="LineCtrl">
                <div class="col-lg-6 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Currency Rate Chart</div>
                        <div class="panel-body">
                            <canvas id="line" class="chart chart-line" data="data" labels="labels" legend="true" series="series" click="onClick"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php var_dump($messages) ?>
        <script>
            window.data = <?php echo json_encode($messages) ?>;
            window.nodeUrl = '<?php echo $value = config('app.nodeUrl'); ?>';
        </script>

        <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
        <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
        <script src="/bower_components/angular/angular.min.js"></script>
        <script src="/bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js"></script>
        <script src="/bower_components/Chart.js/Chart.min.js"></script>
        <script src="/bower_components/angular-chart.js/angular-chart.js"></script>
        <script src="/js/app.js"></script>
    </body>
</html>