app.controller('catalogs-hardwares-controller', function($scope, $http, $location, $rootScope) {
	$scope.isView = true;
	$scope.isForm = false;
	$scope.isEdit = false;
	$scope.isAdd = false;
	$scope.isFormForCharacteristic = false;
	$scope.isEditCharacteristic = false;
	$scope.isAddCharacteristic = false;

	$scope.isActionSuccess = false;
	$scope.messageOfActionSuccess = '';
	$scope.isActionError = false;
	$scope.messageOfActionError = '';

	$scope.hardware_defaults = { id: "", unique_number: "" };
	$scope.hardware = JSON.parse(JSON.stringify($scope.hardware_defaults));
	$scope.last_inserted_hardware = JSON.parse(JSON.stringify($scope.hardware_defaults));

	$scope.hardware_characteristic_defaults = { id: "", hardware_id: "", characteristic_id: "", value: "" };
	$scope.hardware_characteristic = JSON.parse(JSON.stringify($scope.hardware_characteristic_defaults));
	$scope.last_inserted_hardware_characteristic = JSON.parse(JSON.stringify($scope.hardware_characteristic_defaults));

	$http
	.post('php/models/catalogs/catalogs-hardwares-model.php', { action: 'read' })
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
				$scope.is_hardware_has_any_characteristics_map = _.object(_.map($scope.hardwares_characteristics, function(e) { return [e.hardware_id,  true] }))

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
		$scope.hardware = JSON.parse(JSON.stringify($scope.hardware_defaults));
		$scope.isView = false;
		$scope.isForm = true;

		$http
		.post('php/models/catalogs/catalogs-hardwares-model.php', { action: 'create', hardware: $scope.hardware })
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

					$scope.hardware = response.data.hardware;
					$scope.last_inserted_hardware = JSON.parse(JSON.stringify($scope.hardware));

					$scope.isView = false;
					$scope.isForm = true;
					$scope.isEdit = false;
					$scope.isAdd = true;
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

	$scope.Edit = function(hardware) {
		$scope.hardware = JSON.parse(JSON.stringify(hardware));

		$scope.isView = false;
		$scope.isForm = true;
		$scope.isEdit = true;
		$scope.isAdd = false;
	}

	$scope.Delete = function(hardware) {
		$scope.hardware = JSON.parse(JSON.stringify(hardware));

		$http
		.post('php/models/catalogs/catalogs-hardwares-model.php', { action: 'delete', hardware: $scope.hardware })
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
					$scope.hardware = JSON.parse(JSON.stringify($scope.hardware_defaults));
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
		.post('php/models/catalogs/catalogs-hardwares-model.php', { action: 'update', hardware: $scope.hardware })
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
					$scope.hardware = JSON.parse(JSON.stringify($scope.hardware_defaults));
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
		if($scope.isAdd == false) {
			$scope.isView = true;
			$scope.isForm = false;
			$scope.isEdit = false;
			$scope.isAdd = false;
			$scope.hardware = JSON.parse(JSON.stringify($scope.hardware_defaults));
			return;
		}

		$http
		.post('php/models/catalogs/catalogs-hardwares-model.php', { action: 'delete', hardware: $scope.last_inserted_hardware })
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
					$scope.hardware = JSON.parse(JSON.stringify($scope.hardware_defaults));
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

	$scope.AddCharacteristic = function() {
		$scope.hardware_characteristic = JSON.parse(JSON.stringify($scope.hardware_characteristic_defaults));
		$scope.hardware_characteristic.hardware_id = $scope.hardware.id;

		$scope.isForm = false;
		$scope.isFormForCharacteristic = true;
		$scope.isAddCharacteristic = true;
		$scope.isEditCharacteristic= false;
	}

	$scope.EditCharacteristic = function(hardware_characteristic) {
		$scope.hardware_characteristic = JSON.parse(JSON.stringify(hardware_characteristic));

		$scope.isForm = false;
		$scope.isFormForCharacteristic = true;
		$scope.isAddCharacteristic = false;
		$scope.isEditCharacteristic= true;
	}

	$scope.DeleteCharacteristic = function(hardware_characteristic) {
		$scope.hardware_characteristic = JSON.parse(JSON.stringify(hardware_characteristic));
		console.log(hardware_characteristic);
		$http
		.post('php/models/catalogs/catalogs-hardwares-model.php', { action: 'delete_hardware_characteristic', hardware_characteristic: $scope.hardware_characteristic })
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
					$scope.is_hardware_has_any_characteristics_map = _.object(_.map($scope.hardwares_characteristics, function(e) { return [e.hardware_id,  true] }))

					$scope.isActionSuccess = true;
					$scope.messageOfActionSuccess = response.data.message;
					$scope.isActionError = false;
					$scope.messageOfActionError = '';

					$scope.hardware_characteristic = JSON.parse(JSON.stringify($scope.hardware_characteristic_defaults));
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

	$scope.SaveCharacteristic = function() {
		$http
		.post('php/models/catalogs/catalogs-hardwares-model.php', { action: 'update_hardware_characteristic', hardware_characteristic: $scope.hardware_characteristic })
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

					$scope.isForm = true;
					$scope.isFormForCharacteristic = false;
					$scope.isAddCharacteristic = false;
					$scope.isEditCharacteristic= false;
					$scope.hardware_characteristic = JSON.parse(JSON.stringify($scope.hardware_characteristic_defaults));
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

	$scope.SaveNewCharacteristic = function() {
		$http
		.post('php/models/catalogs/catalogs-hardwares-model.php', { action: 'create_hardware_characteristic', hardware_characteristic: $scope.hardware_characteristic })
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
					$scope.is_hardware_has_any_characteristics_map = _.object(_.map($scope.hardwares_characteristics, function(e) { return [e.hardware_id,  true] }))

					$scope.isActionSuccess = true;
					$scope.messageOfActionSuccess = response.data.message;
					$scope.isActionError = false;
					$scope.messageOfActionError = '';

					$scope.isForm = true;
					$scope.isFormForCharacteristic = false;
					$scope.isAddCharacteristic = false;
					$scope.isEditCharacteristic= false;
					$scope.hardware_characteristic = JSON.parse(JSON.stringify($scope.hardware_characteristic_defaults));
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

	$scope.CancelCharacteristic = function() {
		$scope.hardware_characteristic = JSON.parse(JSON.stringify($scope.hardware_characteristic_defaults));
		$scope.isForm = true;
		$scope.isFormForCharacteristic = false;
		$scope.isAddCharacteristic = false;
		$scope.isEditCharacteristic= false;
	}
});
