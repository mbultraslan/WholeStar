KulerModule.value('_tMessages', _tMessages);

KulerModule.controller('FilterCtrl', ['$scope', '$http' ,'$location', '_t', 'shortCode', '$cookies', '$rootScope', function ($scope, $http, $location, _t, shortCode, $cookies, $rootScope) {
	_t.config(Kuler.messages);

	// Hack for post request: http://victorblog.com/2012/12/20/make-angularjs-http-service-behave-like-jquery-ajax/
	$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
	$http.defaults.transformRequest = [function(data) {
		return angular.isObject(data) && String(data) !== '[object File]' ? jQuery.param(data) : data;
	}];

	$scope.loading          = false;

	$scope.store_id         = Kuler.store_id;
	$scope.extensionCode    = Kuler.extensionCode;
	$scope.defaultModule    = Kuler.defaultModule;
	$scope.status           = Kuler.status;
	$scope.modules          = Kuler.modules;
	$scope.languages        = Kuler.languages;
	$scope.configLanguage   = Kuler.configLanguage;

	// Init active showcase for each module
	if (Array.isArray($scope.modules)) {
		$scope.modules.forEach(function (module) {
			module.$$attributeClose = true;
			module.$$optionClose = true;
		});
	}

	$scope.addModule = function () {
		var title = _t.get('text_module') + ' ' + ($scope.modules.length + 1),
			module = angular.copy($scope.defaultModule);

		module.mainTitle = title;
		module.title = {};
		module.active = true;

		module.$$attributeClose = true;
		module.$$optionClose = true;

		angular.forEach($scope.languages, function (language) {
			module.title[language.code] = title;
		});

		module.shortcode = shortCode.generate($scope.extensionCode, title);

		$scope.modules.push(module);
	};

	$scope.removeModule = function (index) {
		$scope.modules.splice(index, 1);
	};

	$scope.onTitleChanged = function (index, title, languageCode) {
		if (languageCode == $scope.configLanguage) {
			$scope.modules[index].mainTitle = title;
			$scope.modules[index].shortcode = shortCode.generate($scope.extensionCode, title);
		}
	};

	$scope.save = function () {
		$scope.loading = true;

		$rootScope.$broadcast('save');

		$http
			.post(Kuler.actionUrl, {
				store_id: $scope.store_id,
				status: $scope.status,
				modules: $scope.modules
			})
			.success(function (data) {
				$scope.messageType = data.status ? 'success' : 'danger';
				$scope.message = data.message;

				$scope.loading = false;
			})
			.error(function (data) {
				$scope.loading = false;
			});
	};

	$scope.onSelectModule = function (index) {
		document.cookie = $scope.extensionCode + '_module=' + index;
	};

	$scope.selectModule = function (index) {
		if (angular.isDefined($scope.modules[index])) {
			$scope.modules[index].active = true;
		}
	};

	$scope.isChecked = function (optionId, moduleIndex, valueId) {
		var index, optionProperty;

		if (optionId === 'attribute_group') {
			optionProperty = 'exclude_attr_group_id';
		} else if (optionId === 'attribute') {
			optionProperty = 'exclude_attr_id';
		} else if (optionId === 'option_group') {
			optionProperty = 'exclude_opt_id';
		} else if (optionId === 'option') {
			optionProperty = 'exclude_opt_value_id';
		}

		if (!Array.isArray($scope.modules[moduleIndex][optionProperty])) {
			$scope.modules[moduleIndex][optionProperty] = [];
		}

		index = $scope.modules[moduleIndex][optionProperty].indexOf(valueId);

		return index !== -1 ? true : false;
	}

	$scope.toggleExclude = function (optionId, moduleIndex, valueId) {
		var index, optionProperty;

		if (optionId === 'attribute_group') {
			optionProperty = 'exclude_attr_group_id';
		} else if (optionId === 'attribute') {
			optionProperty = 'exclude_attr_id';
		} else if (optionId === 'option_group') {
			optionProperty = 'exclude_opt_id';
		} else if (optionId === 'option') {
			optionProperty = 'exclude_opt_value_id';
		}

		if (!Array.isArray($scope.modules[moduleIndex][optionProperty])) {
			$scope.modules[moduleIndex][optionProperty] = [];
		}

		index = $scope.modules[moduleIndex][optionProperty].indexOf(valueId);

		if (index === -1) {
			$scope.modules[moduleIndex][optionProperty].push(valueId);
		} else {
			$scope.modules[moduleIndex][optionProperty].splice(index, 1);
		}
	}

	$scope.selectModule(getActiveModuleIndex());

	$scope.selectStore = function (storeId) {
		location = Kuler.storeUrl + '&store_id=' + storeId;
	};

	function getActiveModuleIndex() {
		return $cookies[$scope.extensionCode + '_module'] || 0;
	}

	function getMultilingualTitle(title) {
		var titles = {};

		angular.forEach($scope.languages, function (language) {
			titles[language.code] = title;
		});

		return titles;
	}
}]);