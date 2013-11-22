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
            var filteredData = $filter('week')($scope.analytics, $scope.selected_week);

            $scope.stats = statsFactory(filteredData);
        }
    }])
    .filter('week', function() {
        return function (input, startOfWeek) {
            var out = {};

            // Loop through all input properties
            for(var property in input) {
                // Check to make sure it's not a prototyped property
                if(input.hasOwnProperty(property)) {
                    // Loop through each item in the array of the current property
                    for(var x = 0; x < input[property].length; x++) {
                        // See if our current record matches the given startOfWeek
                        if(input[property][x].start_of_week === startOfWeek) {
                            // Ensure the return object has an array to push records into
                            if( ! out.hasOwnProperty(property)) {
                                out[property] = [];
                            }

                            // Add record to array
                            out[property].push( input[property][x] );
                        }
                    }
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
        this.sum = function(data) {
            if( ! data.hasOwnProperty('completions')) {
                return 0;
            }

            var completions = [];

            for(var x = 0; x < data.completions.length; x++) {
                completions.push(data.completions[x].goal16Completions);
                completions.push(data.completions[x].goal14Completions);
            }

            var total = function() {
                return completions.reduce(function(previous, current) {
                    return previous + current;
                });
            };

            return completions.length > 0 ? total() : 0;
        }
    })
    .factory('statsFactory', ['dlmptLeads', function(dlmptLeads) {
        return function(filteredData) {
            var stats = { data: filteredData };

            stats.dlmptLeads = dlmptLeads.sum(stats.data);

            return stats;
        };
    }]);
