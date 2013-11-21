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
            $scope.analytics = data.analytics;
        })
        .error(function(data, status, headers, config) {
            console.log('no data could be retrieved: ' + status);
        });

        $scope.changeDate = function() {
            $scope.filteredCompletions = $filter('week')($scope.analytics.completions, $scope.selected_week);

            // Add completions for total DLMPT Leads
            var completions = [];
            $.each($scope.filteredCompletions, function(k, v) {
                completions.push(v.goal16Completions);
                completions.push(v.goal14Completions);
            });

            if(completions.length > 0) {
                $scope.dlmptLeads = completions.reduce(function(previous, current) {
                    return previous + current;
                });
            } else {
                $scope.dlmptLeads = 0;
            }
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
