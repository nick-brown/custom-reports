angular.module('app', ['ngResource'])
    .controller('DropdownCtrl', ['$scope', '$http', '$filter', 'statsFactory', function($scope, $http, $filter, statsFactory) {
        // Consider using $resource instead of $http
        $http({
            method: 'GET',
            url: paths.public + 'api/data'
        })
        .success(function(data, status, headers, config) {
            $scope.materials = data.materials;
            $scope.channelPartners = data.partners;
            $scope.sundays = data.sundays;
            $scope.analytics = data.analytics;
        })
        .error(function(data, status, headers, config) {
            console.log('no data could be retrieved: ' + status);
        });


        $scope.changeDate = function() {
            var filteredCompletions = $filter('week')($scope.analytics.completions, $scope.selected_week);

            $scope.stats = statsFactory(filteredCompletions);
            //console.log($scope.stats);
        }
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
    })
    .filter('partner', function() {
        return function (input, partner) {
            var out = [];

            for(var x = 0; x < input.length; x++) {
                if(input[x].start_of_week === startOfWeek) {
                    out.push(input[x]);
                }
            }

            return out;
        }
    })
    .service('dlmptLeads', function() {
        this.sum = function(filteredData) {
            var completions = [];

            for(var x = 0; x < filteredData.length; x++) {
                completions.push(filteredData[x].goal16Completions);
                completions.push(filteredData[x].goal14Completions);
            }

            var sum = function() {
                return completions.reduce(function(previous, current) {
                    return previous + current;
                });
            };

            return completions.length > 0 ? sum() : 0;
        }
    })
    .factory('statsFactory', ['dlmptLeads', function(dlmptLeads) {
        return function(filteredData) {
            var stats = { data: filteredData };

            stats.dlmptLeads = dlmptLeads.sum(stats.data);


            return stats;
        };
    }]);
