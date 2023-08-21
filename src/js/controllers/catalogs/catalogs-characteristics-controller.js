app.controller('catalogs-characteristics-controller', function($scope, $http, $location, $rootScope) {
	$scope.isView = true;
	$scope.isForm = false;
	$scope.isEdit = false;
	$scope.isAdd = false;

	$scope.isActionSuccess = false;
	$scope.messageOfActionSuccess = '';
	$scope.isActionError = false;
	$scope.messageOfActionError = '';

	$scope.characteristic_defaults = { id: "", name: "" };
	$scope.characteristic = JSON.parse(JSON.stringify($scope.characteristic_defaults));

	$http
	.post('php/models/catalogs/catalogs-characteristics-model.php', { action: 'read' })
	.then
	(
		function(response) {
			var status = response.data.status;
			if(status == 'success')
			{
				$scope.hardwares = response.data.hardwares;
				$scope.hardwares_map = _.object(_.map(response.data.hardwares, function (e) { return [e.id, e]; }));
				$scope.characteristics = response.data.characteristics;
				$scope.characteristics_map = _.object(_.map(response.data.characteristics, function (e) { return [e.id, e]; }));
				$scope.hardwares_characteristics = response.data.hardwares_characteristics;
				$scope.hardwares_characteristics_map = _.object(_.map(response.data.hardwares_characteristics, function (e) { return [e.id, e]; }));

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
		$scope.characteristic = JSON.parse(JSON.stringify($scope.characteristic_defaults));
		$scope.isView = false;
		$scope.isForm = true;
		$scope.isEdit = false;
		$scope.isAdd = true;
	}

	$scope.Edit = function(characteristic) {
		$scope.characteristic = JSON.parse(JSON.stringify(characteristic));
		$scope.isView = false;
		$scope.isForm = true;
		$scope.isEdit = true;
		$scope.isAdd = false;
	}

	$scope.Delete = function(characteristic) {
		$scope.characteristic = JSON.parse(JSON.stringify(characteristic));

		$http
		.post('php/models/catalogs/catalogs-characteristics-model.php', { action: 'delete', characteristic: $scope.characteristic })
		.then
		(
			function(response) {
				var status = response.data.status;
				if(status == 'success')
				{
					$scope.hardwares = response.data.hardwares;
					$scope.hardwares_map = _.object(_.map(response.data.hardwares, function (e) { return [e.id, e]; }));
					$scope.characteristics = response.data.characteristics;
					$scope.characteristics_map = _.object(_.map(response.data.characteristics, function (e) { return [e.id, e]; }));
					$scope.hardwares_characteristics = response.data.hardwares_characteristics;
					$scope.hardwares_characteristics_map = _.object(_.map(response.data.hardwares_characteristics, function (e) { return [e.id, e]; }));

					$scope.isActionSuccess = true;
					$scope.messageOfActionSuccess = response.data.message;
					$scope.isActionError = false;
					$scope.messageOfActionError = '';

					$scope.isView = true;
					$scope.isForm = false;
					$scope.isEdit = false;
					$scope.isAdd = false;
					$scope.characteristic = JSON.parse(JSON.stringify($scope.characteristic_defaults));
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
		$http
		.post('php/models/catalogs/catalogs-characteristics-model.php', { action: 'update', characteristic: $scope.characteristic })
		.then
		(
			function(response) {
				var status = response.data.status;
				if(status == 'success')
				{
					$scope.hardwares = response.data.hardwares;
					$scope.hardwares_map = _.object(_.map(response.data.hardwares, function (e) { return [e.id, e]; }));
					$scope.characteristics = response.data.characteristics;
					$scope.characteristics_map = _.object(_.map(response.data.characteristics, function (e) { return [e.id, e]; }));
					$scope.hardwares_characteristics = response.data.hardwares_characteristics;
					$scope.hardwares_characteristics_map = _.object(_.map(response.data.hardwares_characteristics, function (e) { return [e.id, e]; }));

					$scope.isActionSuccess = true;
					$scope.messageOfActionSuccess = response.data.message;
					$scope.isActionError = false;
					$scope.messageOfActionError = '';

					$scope.isView = true;
					$scope.isForm = false;
					$scope.isEdit = false;
					$scope.isAdd = false;
					$scope.characteristic = JSON.parse(JSON.stringify($scope.characteristic_defaults));
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
		$http
		.post('php/models/catalogs/catalogs-characteristics-model.php', { action: 'create', characteristic: $scope.characteristic })
		.then
		(
			function(response) {
				var status = response.data.status;
				if(status == 'success')
				{
					$scope.hardwares = response.data.hardwares;
					$scope.hardwares_map = _.object(_.map(response.data.hardwares, function (e) { return [e.id, e]; }));
					$scope.characteristics = response.data.characteristics;
					$scope.characteristics_map = _.object(_.map(response.data.characteristics, function (e) { return [e.id, e]; }));
					$scope.hardwares_characteristics = response.data.hardwares_characteristics;
					$scope.hardwares_characteristics_map = _.object(_.map(response.data.hardwares_characteristics, function (e) { return [e.id, e]; }));

					$scope.isActionSuccess = true;
					$scope.messageOfActionSuccess = response.data.message;
					$scope.isActionError = false;
					$scope.messageOfActionError = '';

					$scope.isView = true;
					$scope.isForm = false;
					$scope.isEdit = false;
					$scope.isAdd = false;
					$scope.characteristic = JSON.parse(JSON.stringify($scope.characteristic_defaults));
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
		$scope.characteristic = JSON.parse(JSON.stringify($scope.characteristic_defaults));
	}
});
