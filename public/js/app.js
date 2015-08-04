(function () {
  'use strict';

  var app = angular.module('currencyGraph', ['chart.js', 'ui.bootstrap']);

  app.config(function (ChartJsProvider) {
    // Configure all charts
    ChartJsProvider.setOptions({
      colours: ['#97BBCD', '#DCDCDC', '#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'],
      responsive: true
    });
  });

  app.controller('LineCtrl', ['$scope', '$timeout', 'socket', '$window', function ($scope, $timeout, socket, $window) {
    $scope.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $scope.series = [];
    $scope.data = [];

    $.each($window.data, function(key, value) {
      $scope.series.push(key);
      var seriesData = [[]];

      for (var i = 1; i <= 12; i++) {
        if (typeof value[i] == 'undefined') {
          seriesData[i-1] = null;
        } else {
          seriesData[i-1] = value[i];
        }
      }

      $scope.data.push(seriesData);
    });

    // Display the data received through the socket
    socket.on('message', function (data) {
      data = $.parseJSON(data);

      var currencyFromTo = Object.keys(data)[0];
      var seriesIndex = $scope.series.indexOf(currencyFromTo);
      var monthIndex = parseInt(data[currencyFromTo]['month']) - 1;

      // If the index has not been found, push the data at the end
      if (seriesIndex == -1) {
        seriesIndex = $scope.series.length;
        $scope.series.push(currencyFromTo)
      }

      if (typeof $scope.data[seriesIndex] == 'undefined') {
        $scope.data[seriesIndex] = [];
        // Initialize the array
        for (var i = 0; i < 12; i++) {
          $scope.data[seriesIndex][i] = null;
        }
      }

      console.log($scope.data);

      $scope.data[seriesIndex][monthIndex] = parseFloat(data[currencyFromTo]['rate']);
    });
  }]);

  app.factory('socket', function ($rootScope) {
    var socket = io.connect(window.nodeUrl);

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
