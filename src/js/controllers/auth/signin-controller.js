app.controller('signin-controller', function($scope, $http, $location, $rootScope) {
	$scope.signInData = {};
	$scope.isSignInError = false;
	$scope.signInErrorMessage = '';

	$scope.doSignIn = function(signInData) {
		$http
		.post('php/models/auth/signin-model.php', { action: 'signin', user: signInData.user, password: signInData.password })
		.then
		(
			function(response) {
				var status = response.data.status;
				if(status == 'success')
				{
					$scope.isSignInError = false;
					$scope.signInErrorMessage = '';
					$location.path("/signout");
				}
				else
				{
					$scope.isSignInError = true;
					$scope.signInErrorMessage = response.data.message;
				}
			},
			function(response) {
				$scope.isSignInError = true;
				$scope.signInErrorMessage = 'Ошибка ответа сервера.';
			}
		);
	};
});
