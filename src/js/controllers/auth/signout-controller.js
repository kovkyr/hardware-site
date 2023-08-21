app.controller('signout-controller', function($scope, $http, $location, $rootScope) {
	$scope.isSignOutError = false;
	$scope.signOutErrorMessage = '';

	$scope.doSignOut = function() {
		$http
		.post('php/models/auth/signout-model.php', { action: 'signout' })
		.then
		(
			function(response) {
				var status = response.data.status;
				if(status == 'success')
				{
					$scope.isSignOutError = false;
					$scope.signOutErrorMessage = '';
					$location.path("/signin");
				}
				else
				{
					$scope.isSignOutError = true;
					$scope.signOutErrorMessage = response.data.message;
				}
			},
			function(response) {
				$scope.isSignOutError = true;
				$scope.signOutErrorMessage = 'Ошибка ответа сервера.';
			}
		);
	};
});
