angular.module('app', ['ngResource'])
    .controller('DropdownCtrl', ['$scope', '$resource', function($scope, $resource) {
        var Contact = $resource(paths.public + 'api/data');

        $.get(paths.public + 'api/data', function(data) { console.log(data) }, 'json');
        console.log(Contact);
    }]);
