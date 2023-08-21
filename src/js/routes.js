var app = angular.module("App", ["ngRoute"]);
app
.config(function($routeProvider) {
	$routeProvider
		.when("/", {
			title: 'Главная',
			templateUrl: "html/main.html",
			controller: 'main-controller'
		})
		.when("/signin", {
			title: 'Вход',
			templateUrl: "html/auth/signin.html",
			controller: 'signin-controller'
		})
		.when("/signout", {
			title: 'Выход',
			templateUrl: "html/auth/signout.html",
			controller: 'signout-controller'
		})
		.when("/catalogs-hardwares", {
			title: 'Оборудование',
			templateUrl: "html/catalogs/catalogs-hardwares.html",
			controller: 'catalogs-hardwares-controller'
		})
		.when("/catalogs-characteristics", {
			title: 'Характеристики',
			templateUrl: "html/catalogs/catalogs-characteristics.html",
			controller: 'catalogs-characteristics-controller'
		})
		.when("/catalogs-report", {
			title: 'Отчет',
			templateUrl: "html/catalogs/catalogs-report.html",
			controller: 'catalogs-report-controller'
		})
		.when("/catalogs-users", {
			title: 'Учетные записи',
			templateUrl: "html/catalogs/catalogs-users.html",
			controller: 'catalogs-users-controller'
		});
})
.run(function($rootScope, $http, $location, $timeout) {
	$rootScope.authenticated = false;
	$rootScope.user = 'Пользователь';

	$rootScope.$on("$locationChangeStart", function(event, next, current) {
		$rootScope.isAuthenticated();
	});

	$rootScope.$on("$routeChangeSuccess", function(event, currentRoute, previousRoute){
		$rootScope.title = currentRoute.title;
	});

	$rootScope.isAuthenticated = function() {
		$http
		.post('php/routes.php', { action: 'isauthenticated' })
		.then
		(
			function(response) {
				$rootScope.authenticated = response.data.authenticated == "true";
				$rootScope.user = response.data.user;
			},
			function(response) {
				$rootScope.authenticated = false;
				$rootScope.user = 'Пользователь';
			}
		);
	};
});

app.controller('routes-controller', function($scope, $location, $http) {
	$scope.IsItLocationPath = function (viewLocation) {
		return viewLocation === $location.path();
	};
});
