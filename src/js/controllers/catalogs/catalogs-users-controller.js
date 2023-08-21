app.controller('catalogs-users-controller', function($scope, $http, $location, $rootScope) {
	$scope.isView = true;
	$scope.isForm = false;
	$scope.isEdit = false;
	$scope.isAdd = false;

	$scope.isActionSuccess = false;
	$scope.messageOfActionSuccess = '';
	$scope.isActionError = false;
	$scope.messageOfActionError = '';

	$scope.user = [];
	$scope.user_old = [];
	$scope.user_defaults = { id: "", user: "", password: null, password_repeat: null }
	$scope.users = [];
	$scope.users_map = [];

	$http
	.post('php/models/catalogs/catalogs-users-model.php', { action: 'read' })
	.then
	(
		function(response) {
			var status = response.data.status;
			if(status == 'success')
			{
				$scope.users = response.data.users;
				$scope.users_map = _.object(_.map(response.data.users, function (e) { return [e.id, e]; }));

				$scope.isActionSuccess = true;
				$scope.messageOfActionSuccess = response.data.message;
				$scope.isActionError = false;
				$scope.messageOfActionError = '';
			}
			else
			{
				$scope.isActionSuccess = false;
				$scope.messageOfActionSuccess = '';
				$scope.isActionError = true;
				$scope.messageOfActionError = response.data.message;
			}
		},
		function(response) {
			$scope.isActionSuccess = false;
			$scope.messageOfActionSuccess = '';
			$scope.isActionError = true;
			$scope.messageOfActionError = 'Ошибка ответа сервера.';
		}
	);

	$scope.Add = function() {
		$scope.user = JSON.parse(JSON.stringify($scope.user_defaults));
		$scope.isView = false;
		$scope.isForm = true;
		$scope.isEdit = false;
		$scope.isAdd = true;
	}

	$scope.Edit = function(user) {
		$scope.user = JSON.parse(JSON.stringify(user));
		$scope.user_old = JSON.parse(JSON.stringify(user));
		$scope.isView = false;
		$scope.isForm = true;
		$scope.isEdit = true;
		$scope.isAdd = false;
	}

	$scope.Delete = function(user) {
		$scope.user = JSON.parse(JSON.stringify(user));

		$http
		.post('php/models/catalogs/catalogs-users-model.php', { action: 'delete', user: $scope.user })
		.then
		(
			function(response) {
				var status = response.data.status;
				if(status == 'success')
				{
					$scope.users = response.data.users;
					$scope.users_map = _.object(_.map(response.data.users, function (e) { return [e.id, e]; }));

					$scope.isActionSuccess = true;
					$scope.messageOfActionSuccess = response.data.message;
					$scope.isActionError = false;
					$scope.messageOfActionError = '';

					$scope.isView = true;
					$scope.isForm = false;
					$scope.isEdit = false;
					$scope.isAdd = false;
					$scope.user = JSON.parse(JSON.stringify($scope.user));
				}
				else
				{
					$scope.isActionSuccess = false;
					$scope.messageOfActionSuccess = '';
					$scope.isActionError = true;
					$scope.messageOfActionError = response.data.message;
				}
			},
			function(response) {
				$scope.isActionSuccess = false;
				$scope.messageOfActionSuccess = '';
				$scope.isActionError = true;
				$scope.messageOfActionError = 'Ошибка ответа сервера.';
			}
		);
	}

	$scope.Save = function() {
		if ($scope.user.password !== $scope.user.password_repeat) {
			$scope.isActionSuccess = false;
			$scope.messageOfActionSuccess = '';
			$scope.isActionError = true;
			$scope.messageOfActionError = 'Пароли не совпадают.';
			return;
		}

		$http
		.post('php/models/catalogs/catalogs-users-model.php', { action: 'update', user: $scope.user })
		.then
		(
			function(response) {
				var status = response.data.status;
				if(status == 'success')
				{
					$scope.users = response.data.users;
					$scope.users_map = _.object(_.map(response.data.users, function (e) { return [e.id, e]; }));

					$scope.isActionSuccess = true;
					$scope.messageOfActionSuccess = response.data.message;
					$scope.isActionError = false;
					$scope.messageOfActionError = '';

					if ($scope.user.user !== $scope.user_old.user && $scope.user_old.user == $rootScope.user) {
						$rootScope.user = $scope.user.user;
					}

					$scope.isView = true;
					$scope.isForm = false;
					$scope.isEdit = false;
					$scope.isAdd = false;
					$scope.user = JSON.parse(JSON.stringify($scope.user_defaults));
				}
				else
				{
					$scope.isActionSuccess = false;
					$scope.messageOfActionSuccess = '';
					$scope.isActionError = true;
					$scope.messageOfActionError = response.data.message;
				}
			},
			function(response) {
				$scope.isActionSuccess = false;
				$scope.messageOfActionSuccess = '';
				$scope.isActionError = true;
				$scope.messageOfActionError = 'Ошибка ответа сервера.';
			}
		);
	}

	$scope.Create = function() {
		if ($scope.user.password !== $scope.user.password_repeat) {
			$scope.isActionSuccess = false;
			$scope.messageOfActionSuccess = '';
			$scope.isActionError = true;
			$scope.messageOfActionError = 'Пароли не совпадают.';
			return;
		}

		$http
		.post('php/models/catalogs/catalogs-users-model.php', { action: 'create', user: $scope.user })
		.then
		(
			function(response) {
				var status = response.data.status;
				if(status == 'success')
				{
					$scope.users = response.data.users;
					$scope.users_map = _.object(_.map(response.data.users, function (e) { return [e.id, e]; }));

					$scope.isActionSuccess = true;
					$scope.messageOfActionSuccess = response.data.message;
					$scope.isActionError = false;
					$scope.messageOfActionError = '';

					$scope.isView = true;
					$scope.isForm = false;
					$scope.isEdit = false;
					$scope.isAdd = false;
					$scope.user = JSON.parse(JSON.stringify($scope.user_defaults));
				}
				else
				{
					$scope.isActionSuccess = false;
					$scope.messageOfActionSuccess = '';
					$scope.isActionError = true;
					$scope.messageOfActionError = response.data.message;
				}
			},
			function(response) {
				$scope.isActionSuccess = false;
				$scope.messageOfActionSuccess = '';
				$scope.isActionError = true;
				$scope.messageOfActionError = 'Ошибка ответа сервера.';
			}
		);
	}

	$scope.Cancel = function() {
		$scope.isView = true;
		$scope.isForm = false;
		$scope.isEdit = false;
		$scope.isAdd = false;
		$scope.user = JSON.parse(JSON.stringify($scope.user_defaults));
		$scope.user_old = JSON.parse(JSON.stringify($scope.user_defaults));
	}
});
