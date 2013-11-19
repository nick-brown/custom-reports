angular.module('app', ['ngResource'])
    .controller('DropdownCtrl', ['$scope', '$http', function($scope, $http) {
        // Consider using $resource instead of $http
        $http({
            method: 'GET',
            url: paths.public + 'api/data'
        })
        .success(function(data, status, headers, config) {
            $scope.materials = data.materials;
            $scope.channelPartners = data.partners;
            $scope.sundays = data.sundays;
        })
        .error(function(data, status, headers, config) {
            console.log('no data could be retrieved: ' + status);
        });
    }])
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
