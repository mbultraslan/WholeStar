'use strict';

$(document).ready(function () {
	setTimeout(function () {
		// Override summernotes image manager
		$('button[data-event=\'showImageDialog\']').attr('data-toggle', 'image').removeAttr('data-event');
	}, 500);
});

var KulerModule = angular.module('kulerModule', ['ngSanitize', 'ngCookies', 'angularFileUpload', 'ui.bootstrap', 'summernote', 'ui.autocomplete', 'ngDragDrop', 'ui.slider', 'ui.sortable']);

KulerModule.filter('trusted', ['$sce', function ($sce) {
	return function(url) {
		return $sce.trustAsResourceUrl(url);
	};
}]);