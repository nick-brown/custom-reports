angular.module('app', ['ngResource'])
    .controller('DropdownCtrl', ['$scope', '$http', '$filter', function($scope, $http, $filter) {
        // Consider using $resource instead of $http
        $http({
            method: 'GET',
            url: paths.public + 'api/data'
        })
        .success(function(data, status, headers, config) {
            $scope.materials = data.materials;
            $scope.channelPartners = data.partners;
            $scope.sundays = data.sundays;

            // Add completions for total DLMPT Leads
            var completions = [];
            $.each(data.analytics.completions, function(k, v) {
                completions.push(v.goal16Completions);
                completions.push(v.goal14Completions);
            });

            var sum = completions.reduce(function(previous, current) {
                return previous + current;
            });

            var selectedDates = $filter('week')(data.analytics.completions, '2013-11-03');
            console.log(selectedDates);

        })
        .error(function(data, status, headers, config) {
            console.log('no data could be retrieved: ' + status);
        });
    }])
    .filter('week', function() {
        return function (input, startOfWeek) {
            var out = [];

            for(var x = 0; x < input.length; x++) {
                if(input[x].start_of_week === startOfWeek) {
                    out.push(input[x]);
                }
            }

            return out;
        }
    });
//    .service('sharedProperties', function () {
//        var partner = '';
//
//        return {
//            getPartner: function () {
//                return partner;
//            },
//            setProperty: function(value) {
//                partner = value;
//            }
//        };
//    });
