KulerModule.value('_tMessages', _tMessages);

KulerModule.directive('layer', function () {
	function isActive(layerIndex) {
		return layerIndex == activeLayerIndex;
	}

	function updatePosition(layer, element) {
		var left, top, right, bottom;

		if (layer.layer_hoffset !== '-') {
			layer.layer_hoffset = parseInt(layer.layer_hoffset);
		}

		if (layer.layer_voffset !== '-') {
			layer.layer_voffset = parseInt(layer.layer_voffset);
		}

		console.log(element, element.outerWidth(), element.outerHeight());

		if (layer.layer_left === 'left' && layer.layer_top == 'top') {
			left = layer.layer_hoffset;
			top = layer.layer_voffset;
			right = 'auto';
			bottom = 'auto';
		} else if (layer.layer_left === 'center' && layer.layer_top == 'top') {
			left = (Kuler.slideWidth - element.outerWidth()) / 2 + layer.layer_hoffset;
			top = layer.layer_voffset;
			right = 'auto';
			bottom = 'auto';
		} else if (layer.layer_left === 'right' && layer.layer_top == 'top') {
			left = 'auto';
			top = layer.layer_voffset;
			right = layer.layer_hoffset;
			bottom = 'auto';
		} else if (layer.layer_left === 'left' && layer.layer_top == 'center') {
			left = layer.layer_hoffset;
			top = (Kuler.slideHeight - element.outerHeight()) / 2 + layer.layer_voffset;
			right = 'auto';
			bottom = 'auto';
		} else if (layer.layer_left === 'center' && layer.layer_top == 'center') {
			left = (Kuler.slideWidth - element.outerWidth()) / 2 + layer.layer_hoffset;
			top = (Kuler.slideHeight - element.outerHeight()) / 2 + layer.layer_voffset;
			right = 'auto';
			bottom = 'auto';
		} else if (layer.layer_left === 'right' && layer.layer_top == 'center') {
			left = 'auto';
			top = (Kuler.slideHeight - element.outerHeight()) / 2 + layer.layer_voffset;
			right = layer.layer_hoffset;
			bottom = 'auto';
		} else if (layer.layer_left === 'left' && layer.layer_top == 'bottom') {
			left = layer.layer_hoffset;
			top = 'auto';
			right = 'auto';
			bottom = layer.layer_voffset;
		} else if (layer.layer_left === 'center' && layer.layer_top == 'bottom') {
			left = (Kuler.slideWidth - element.outerWidth()) / 2 + layer.layer_hoffset;
			top = 'auto';
			right = 'auto';
			bottom = layer.layer_voffset;
		} else if (layer.layer_left === 'right' && layer.layer_top == 'bottom') {
			left = 'auto';
			top = 'auto';
			right = layer.layer_hoffset;
			bottom = layer.layer_voffset;
		}

		if (left !== 'auto') {
			left = parseInt(left);
		}
		if (top !== 'auto') {
			top = parseInt(top);
		}
		if (right !== 'auto') {
			right = parseInt(right);
		}
		if (bottom !== 'auto') {
			bottom = parseInt(bottom);
		}

		element.css({
			left: left,
			top: top,
			right: right,
			bottom: bottom
		});
	}

	var activeLayerIndex;

	return {
		restrict: 'EA',
		link: function (scope, element, attributes) {
			var layerIndex = attributes.layerIndex;

			Kuler.slideWidth = parseInt(Kuler.slideWidth);
			Kuler.slideHeight = parseInt(Kuler.slideHeight);

			// TODO: Improve update position
			$(window).on('load', function () {
				updatePosition(scope.layers[attributes.layer], element);
			});

			scope.$on('layerDrag', function (event, position) {
				if (isActive(layerIndex)) {
					position.left = parseInt(position.left);
					position.top = parseInt(position.top);

					if (scope.activeLayer.layer_left === 'left') {
						scope.activeLayer.layer_hoffset = position.left;
					} else if (scope.activeLayer.layer_left === 'center') {
						scope.activeLayer.layer_hoffset = position.left - ((Kuler.slideWidth - element.outerWidth()) / 2);
					} else if (scope.activeLayer.layer_left === 'right') {
						scope.activeLayer.layer_hoffset = Kuler.slideWidth - (position.left + element.outerWidth());
					}

					if (scope.activeLayer.layer_top === 'top') {
						scope.activeLayer.layer_voffset = position.top;
					} else if (scope.activeLayer.layer_top === 'center') {
						scope.activeLayer.layer_voffset = position.top - ((Kuler.slideHeight - element.outerHeight()) / 2);
					} else if (scope.activeLayer.layer_top === 'bottom') {
						scope.activeLayer.layer_voffset = Kuler.slideHeight - (position.top + element.outerHeight());
					}
				}
			});

			scope.$watch('activeLayer', function (layer) {
				if (isActive(layerIndex)) {
					updatePosition(layer, element);
				}
			}, true);

			scope.$on('activeLayerChanged', function (event, _activeLayerIndex) {
				activeLayerIndex = _activeLayerIndex;
			});
		}
	};
});

KulerModule
	.controller('LayerSliderCtrl', ['$scope', '$http' ,'$location', '_t', 'shortCode', '$cookies', '$rootScope', function ($scope, $http, $location, _t, shortCode, $cookies, $rootScope) {
		_t.config(Kuler.messages);

		// Hack for post request: http://victorblog.com/2012/12/20/make-angularjs-http-service-behave-like-jquery-ajax/
		$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
		$http.defaults.transformRequest = [function(data) {
			return angular.isObject(data) && String(data) !== '[object File]' ? jQuery.param(data) : data;
		}];

		$scope.showMessage = function (type, message) {
			// TODO: Improve error alert
			if (type == 'error') {
				$scope.messageType = 'danger';
				$scope.message = message;
			} else if (type == 'success') {
				$scope.messageType = 'success';
				$scope.message = message;
			}
		};

		$scope.prepareSlideData = function () {
			var slideOptionData = {}, slideData = {}, layersData = [];

			// Remove private properties $$
			angular.copy($scope.slide_option, slideOptionData);
			angular.copy($scope.slide, slideData);
			angular.copy($scope.layers, layersData);

			$scope.slide_option_data = JSON.stringify(slideOptionData);
			$scope.slide_data = JSON.stringify(slideData);
			$scope.layers_data = JSON.stringify(layersData);
		};

		// Save slide & layer data via AJAX
		$scope.save = function () {
			$scope.loading = true;

			$scope.prepareSlideData();

			$http.post(Kuler.save_url, $.param({
				slide_option_data: $scope.slide_option_data,
				slide_data: $scope.slide_data,
				layers_data: $scope.layers_data
			}))
			.success(function (data) {
				if (data.status) {
					$scope.showMessage('success', data.message);
				}

				$scope.loading = false;

				if (data.redirect) {
					location.href = data.redirect;
				}
			})
			.error(function (data) {
				$scope.showMessage('error', data.error);
				$scope.loading = false;
			});
		};

		$scope.setActiveLayer = function (activeLayer) {
			var activeLayerIndex;

			angular.forEach($scope.layers, function (layer, index) {
				layer.$$active = false;

				if (activeLayer === layer) {
					activeLayerIndex = index;
				}
			});

			activeLayer.$$active = true;
			$scope.activeLayer = activeLayer;

			$scope.$broadcast('activeLayerChanged', activeLayerIndex);
		};

		$scope.addLayer = function (type, data) {
			data = data || {};

			var length = $scope.layers.length + 1;

			var layer = {
				layer_type: type,
				layer_caption: type + ' ' + length,
				time_start: length * 300,
				layer_left: 'left',
				layer_top: 'top',
				layer_hoffset: 0,
				layer_voffset: 0,
				layer_animation: 'tp-fade',
				layer_easing: 'Power3.easeInOut',
				layer_split: 'none',
				layer_speed: 300,
				layer_endanimation: 'auto',
				layer_endeasing: 'nothing',
				layer_endspeed: 300,
				layer_endsplit: 'none',
				$$hide: false
			};

			if (type == 'text') {
				layer.layer_class = $scope.layerStyles[0];
			}

			layer = angular.extend(layer, data);

			$scope.layers.push(layer);
			$scope.updateLayerData();

			$scope.setActiveLayer(layer);
		};

		$scope.deleteActiveLayer = function () {
			angular.forEach($scope.layers, function (layer, index) {
				if (layer.$$active) {
					$scope.layers.splice(index, 1);
				}
			});

			$scope.activeLayer = null;
		};

		$scope.updateLayerData = function () {
			angular.forEach($scope.layers, function (layer) {
				var endTime;

				if (!layer.layer_endtime || layer.layer_endtime == '0') {
					endTime = parseInt($scope.slide.$$delay);
				} else {
					endTime = parseInt(layer.layer_endtime);
				}

				layer.$$time_range = [parseInt(layer.time_start), endTime];

				if (layer.layer_type === 'image') {
					layer.$$thumb = Kuler.front_base + 'image/' + layer.layer_content;
				}
			});
		};

		$scope.updateSlideDelay = function () {
			if (!$scope.slide.slider_delay || $scope.slide.slider_delay == '0') {
				$scope.slide.$$delay = parseInt(Kuler.slider.params.delay);
			} else {
				$scope.slide.$$delay = parseInt($scope.slide.slider_delay);
			}
		};

		// Initialize
		$scope.loading = false;

		$scope.layerStyles = Kuler.layerStyles;
		$scope.activeLayer = null;
		$scope.layerImagePlaceholder = '';

		$scope.slide_option = Kuler.slide_option;
		$scope.slide = Kuler.slide;
		$scope.layers = Kuler.layers;

		$scope.updateLayerData();

		// Update layer position in preview
		if ($scope.layers.length) {
			$scope.setActiveLayer($scope.layers[0]);
		}

		// Update layer when slider delay is changed
		$scope.$watch('slide.slider_delay', function () {
			$scope.updateSlideDelay();
			$scope.updateLayerData();
		});

		// Create new image when layerImagePlaceholder is changed
		$scope.$watch('layerImagePlaceholder', function (val) {
			if (val) {
				$scope.addLayer('image', {
					layer_content: val
				});
			}
		});

		$scope.$broadcast('layerPositions');
	}])
	.controller('SlidePreviewCtrl', ['$rootScope', '$scope', function ($rootScope, $scope) {
		$scope.onLayerDrag = function (event, data) {
			$rootScope.$broadcast('layerDrag', data.position);
		};

		// Update preview image
		$scope.$watch('slide.slider_image', function (val) {
			$scope.slide.$$slider_image_thumb = Kuler.front_base + 'image/' + val;
		});
	}])
	.controller('LayerOptionCtrl', ['$scope', function ($scope) {
		$scope.alignActiveLayer = function (x, y, offsetX, offsetY) {
			$scope.activeLayer.layer_left = x;
			$scope.activeLayer.layer_top = y;
			$scope.activeLayer.layer_hoffset = offsetX || 0;
			$scope.activeLayer.layer_voffset = offsetY || 0;
		};

		// Check align button is active or not
		$scope.isAlignActive = function (x, y) {
			return $scope.activeLayer && x === $scope.activeLayer.layer_left && y === $scope.activeLayer.layer_top;
		};

		// Clear style of active layer
		$scope.clearActiveLayerStyle = function () {
			$scope.activeLayer.layer_class = '';
		};
	}])
	.controller('LayerListCtrl', ['$scope', function ($scope) {
		// Sortable options
		$scope.layerSortableOptions = {
			axis: 'y'
		};

		// Time slider options
		$scope.timeSliderOptions = {
			range: true,
			stop: function (event, ui) {
				$scope.$apply(function () {
					$scope.activeLayer.time_start = ui.values[0];

					if (ui.values[1] != $scope.slide.$$delay || $scope.activeLayer.layer_endtime) {
						$scope.activeLayer.layer_endtime = ui.values[1];
					}
				});
			}
		};

		$scope.toggleLayer = function (layer) {
			layer.$$hide = !layer.$$hide;
		};
	}]);