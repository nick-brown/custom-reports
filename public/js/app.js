angular.module('app', ['ngResource'])
    .controller('DropdownCtrl', ['$scope', '$http', '$filter', 'statsFactory', function($scope, $http, $filter, statsFactory) {
        // Consider using $resource instead of $http
        $http.get(paths.public + 'api/dates').then(function(request) {
            $scope.sundays = request.data;
        });
//        $http({
//            method: 'GET',
//            url: paths.public + 'api/data'
//        })
//        .success(function(data, status, headers, config) {
//            $scope.materials = data.materials;
//            $scope.channelPartners = data.partners;
//            $scope.sundays = data.sundays;
//
//        })
//        .error(function(data, status, headers, config) {
//            console.log('no data could be retrieved: ' + status);
//        });

        $scope.changeDate = function() {
            $http.get(paths.public + 'api/search', { params: { startOfWeek: $scope.selected_week }}).then(function(request){
                $scope.data = request.data;
            });
            //var filteredData = $filter('selectedParameters')($scope.analytics, 'start_of_week', $scope.selected_week);

            //$scope.stats = statsFactory(filteredData);
        }
    }])
    .filter('selectedParameters', function() {
        return function (input, propertyName, value) {
            var out = {
                completions: [],
                events: []
            };

            // Loop through all input properties (usually just completions and events
            for(var property in input) {
                // Check to make sure it's not a prototyped property
                if(input.hasOwnProperty(property)) {
                    // Loop through each item in the array of the current property
                    for(var x = 0; x < input[property].length; x++) {
                        // See if our current record matches the given startOfWeek
                        if(input[property][x][propertyName] === value) {
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
    .service('totals', function() {
        // Private sum() method
        var sum = function(arr) {
            return arr.reduce(function(previous, current) {
                return previous + current;
            });
        };

        this.dlmptLeads = function(data) {
            var completions = [];

            for(var x = 0; x < data.completions.length; x++) {
                completions.push(data.completions[x].goal16Completions);
                completions.push(data.completions[x].goal14Completions);
            }

            return completions.length > 0 ? sum(completions) : 0;
        }

        this.clicks = function(data, category) {
            var clicks = 0;

            for(var x = 0; x < data.events.length; x++) {
                if(data.events[x].eventCategory.toLowerCase() === category.toLowerCase()) {
                    clicks += data.events[x].totalEvents;
                }
            }

            return clicks;
        }

        this.views = function(data, viewType) {
            var pageviews = 0;

            for(var x = 0; x < data.completions.length; x++) {
                pageviews += data.completions[x][viewType];
            }

            return pageviews;
        }
    })
    .factory('statsFactory', ['totals', function(totals) {
        return function(filteredData) {
            var stats = { data: filteredData };

            stats.dlmptLeads = totals.dlmptLeads(stats.data);
            stats.imageClicks = totals.clicks(stats.data, 'image thumbnail');
            stats.buttonClicks = totals.clicks(stats.data, 'call to action buttons');
            stats.uniquePageviews = totals.views(stats.data, 'uniquePageviews');
            stats.pageviews = totals.views(stats.data, 'pageviews');

            return stats;
        };
    }]);