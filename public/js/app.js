(function () {
  'use strict';

  var app = angular.module('currencyGraph', ['chart.js', 'ui.bootstrap']);

  app.config(function (ChartJsProvider) {
    // Configure all charts
    ChartJsProvider.setOptions({
      colours: ['#97BBCD', '#DCDCDC', '#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'],
      responsive: true
    });
    // Configure all doughnut charts
    ChartJsProvider.setOptions('Doughnut', {
      animateScale: true
    });
  });

  app.controller('DataTablesCtrl', function ($scope, socket, $window) {
    $scope.labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
    $scope.data = [
      [65, 59, 80, 81, 56, 55, 40],
      [65, 59, 80, 90, 56, 55, 40],
    ];
    //$scope.data = $window.data;
    $scope.colours = [
      { // grey
        fillColor: 'rgba(148,159,177,0.2)',
        strokeColor: 'rgba(148,159,177,1)',
        pointColor: 'rgba(148,159,177,1)',
        pointStrokeColor: '#fff',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(148,159,177,0.8)'
      },
      { // dark grey
        fillColor: 'rgba(77,83,96,0.2)',
        strokeColor: 'rgba(77,83,96,1)',
        pointColor: 'rgba(77,83,96,1)',
        pointStrokeColor: '#fff',
        pointHighlightFill: '#fff',
        pointHighlightStroke: 'rgba(77,83,96,1)'
      }
    ];
    //$scope.randomize = function () {
    //  $scope.data = $scope.data.map(function (data) {
    //    return data.map(function (y) {
    //      y = y + Math.random() * 10 - 5;
    //      return parseInt(y < 0 ? 0 : y > 100 ? 100 : y);
    //    });
    //  });
    //};

    socket.on('message', function (data) {
      $scope.data = [
          [65, 65, 65, 65, 56, 55, 40]
        ];
    });
  });

  app.controller('LineCtrl', ['$scope', '$timeout', 'socket', '$window', function ($scope, $timeout, socket, $window) {
    $scope.labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August'];
    //$scope.series = ['Series A', 'Series B'];
    $scope.series = [];
    $scope.data = [];

    $.each($window.data, function(key, value) {
      $scope.series.push(key);
      var seriesData = [];

      for (var i = 1; i <= 12; i++) {
        if (typeof value[i] == 'undefined') {
          seriesData[i-1] = null;
        } else {
          seriesData[i-1] = value[i];
        }
      }

      $scope.data.push(seriesData);
    });

    $scope.onClick = function (points, evt) {
      console.log(points, evt);
    };
    $scope.onHover = function (points) {
      if (points.length > 0) {
        console.log('Point', points[0].value);
      } else {
        console.log('No point');
      }
    };

    $timeout(function () {
      $scope.labels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August'];
      //$scope.data = [
      //  [28, 48, 40, 19, 86, 27, 90],
      //  [65, 59, 80, 81, 56, 55, 40]
      //];
      //$scope.series = ['Series C', 'Series D'];
    }, 3000);

    socket.on('message', function (data) {
      data = $.parseJSON(data);
      var seriesIndex = $scope.series.indexOf(Object.keys(data)[0]);
      var dataArray = $scope.data[seriesIndex];
      $scope.data[seriesIndex[data.month]] = data.avg_rate;

      // TODO: update the proper month
      console.log($scope.data[seriesIndex[data.month]]);
      //console.log(dataArray);
      //console.log(seriesIndex);
      //$scope.data = [
      //  [65, 65, 65, 65, 56, 55, 40]
      //];
    });
  }]);

  app.factory('socket', function ($rootScope) {
    var socket = io.connect('http://localhost:8890');
    return {
      on: function (eventName, callback) {
        socket.on(eventName, function () {
          var args = arguments;
          $rootScope.$apply(function () {
            callback.apply(socket, args);
          });
        });
      },
      emit: function (eventName, data, callback) {
        socket.emit(eventName, data, function () {
          var args = arguments;
          $rootScope.$apply(function () {
            if (callback) {
              callback.apply(socket, args);
            }
          });
        })
      }
    };
  });
})();
