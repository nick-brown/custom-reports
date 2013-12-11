var app = angular.module('app', ['ngResource'])
    .controller('DropdownCtrl', ['$scope', '$http', '$filter', 'stats', 'storage', 'list', function($scope, $http, $filter, stats, storage, list) {
        // Consider using $resource instead of $http
        $scope.sundays = storage.sundays;

        $scope.changeDate = function() {
            var filtered = $filter('selectedParameters')(storage.analytics, 'start_of_week', $scope.selected_week);

            $scope.channelPartners = list.getPartners(filtered);
            $scope.materials = list.getMaterials(filtered);

            $scope.stats = stats.getStatistics(filtered);

            $scope.filteredByWeek = filtered;
        };

        $scope.changePartner = function() {
            var filtered = $scope.filteredByWeek;

            if($scope.selected_partner) {
                filtered = $filter('selectedParameters')(filtered, 'customVarValue1', $scope.selected_partner);
            }

            $scope.materials = list.getMaterials(filtered);

            if($scope.selected_material) {
                filtered = $filter('selectedParameters')(filtered, 'customVarValue2', $scope.selected_material);
            }

            $scope.stats = stats.getStatistics(filtered);
        }

        $scope.changeMaterial = function() {
            var filtered = $scope.filteredByWeek;

            if($scope.selected_material) {
                filtered = $filter('selectedParameters')(filtered, 'customVarValue2', $scope.selected_material);
            }

            $scope.channelPartners = list.getPartners(filtered);

            if($scope.selected_partner) {
                filtered = $filter('selectedParameters')(filtered, 'customVarValue1', $scope.selected_partner);
            }


            $scope.stats = stats.getStatistics(filtered);
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
    .service('list', function() {
        var array_unique = function(inputArr) {
            var key = '',
                tmp_arr2 = [],
                val = '';

            var __array_search = function (needle, haystack) {
                var fkey = '';
                for (fkey in haystack) {
                    if (haystack.hasOwnProperty(fkey)) {
                        if ((haystack[fkey] + '') === (needle + '')) {
                            return fkey;
                        }
                    }
                }
                return false;
            };

            for (key in inputArr) {
                if (inputArr.hasOwnProperty(key)) {
                    val = inputArr[key];
                    if (false === __array_search(val, tmp_arr2)) {
                        tmp_arr2[key] = val;
                    }
                }
            }

            return tmp_arr2;
        }

        this.getPartners = function(filteredData) {
            var partners = [];

            for(var property in filteredData) {
                if(filteredData.hasOwnProperty(property)) {
                    for(var x = 0; x < filteredData[property].length; x++) {
                        partners.push(filteredData[property][x].customVarValue1);
                    }
                }
            }

            return array_unique(partners).filter(function(n){return n});
        };

        this.getMaterials = function(filteredData) {
            var materials = [];

            for(var property in filteredData) {
                if(filteredData.hasOwnProperty(property)) {
                    for(var x = 0; x < filteredData[property].length; x++) {
                        materials.push(filteredData[property][x].customVarValue2);
                    }
                }
            }

            return array_unique(materials).filter(function(n){return n});
        };


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
    .service('stats', ['totals', function(totals) {
        this.stats = {};

        this.getStatistics = function(filteredData) {
            this.stats.dlmptLeads = totals.dlmptLeads(filteredData);
            this.stats.allLeads = totals.views(filteredData, 'goalCompletionsAll');
            this.stats.imageClicks = totals.clicks(filteredData, 'image thumbnail');
            this.stats.buttonClicks = totals.clicks(filteredData, 'call to action buttons');
            this.stats.uniquePageviews = totals.views(filteredData, 'uniquePageviews');
            this.stats.pageviews = totals.views(filteredData, 'pageviews');

            return this.stats;
        }
    }])
    .controller('MonthlyCtrl', ['$scope', 'storage', function($scope, storage) {
        $scope.months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        var today = new Date();
        var thisYear = today.getFullYear();
        var lastYear = thisYear - 1;
        var completions = storage.analytics.completions;
        var data = {
            thisYear: [],
            lastYear: []
        };

        var sum = function(arr) {
            if(arr.length > 0) {
                return arr.reduce(function(previous, current) {
                    return previous + current;
                });
            }
        };


        // Add months objects to each year
        for(var x = 0; x < $scope.months.length; x++) {
           console.log($scope.months[x]);
           data['thisYear'][$scope.months[x]] = [];
           data['lastYear'][$scope.months[x]] = [];
        }


        var getDateInfo = function(startOfWeek) {
            var exploded = startOfWeek.split('-');

            return {
                year: exploded[0],
                month: exploded[1],
                startDay: exploded[2],
                getMonthName: function() {
                    return $scope.months[this.month - 1];
                }
            }
        };

        // Records need to be inserted into the correct year
        for(var x = 0; x < completions.length; x++) {
            var dateInfo = getDateInfo(completions[x].start_of_week);

            if(dateInfo.year == thisYear) {
                var currYear = 'thisYear';
            } else if(dateInfo.year == lastYear) {
                var currYear = 'lastYear';
            }

            // If the record is from the current or previous year, push it to the data object under the corresponding month
            if(currYear) {
                data[currYear][dateInfo.getMonthName()].push(completions[x].goalCompletionsAll);
            }

        }

        // Sum all the arrays
        for(var year in data) {
            for(var month in data[year]) {
                data[year][month] = sum(data[year][month]);
            }
        }

        $scope.leadsByMonth = data;
    }]);