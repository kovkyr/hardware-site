app.controller('catalogs-report-controller', function($scope, $http, $location, $rootScope) {
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
	.post('php/models/catalogs/catalogs-report-model.php', { action: 'read' })
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
				$scope.report = response.data.report;
				$scope.report_map = _.object(_.map(response.data.report,
					function (e)
					{
						return [
							e.hardware_id,
							_.object
							(
								_.zip
								(
									e.characteristics_names.split(';'),
									e.characteristics_values.split(';')
								)
							)
						];
					}
				));

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
});
