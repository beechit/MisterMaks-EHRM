(function() {
	'use strict';

	App.BreadcrumbMenuController  = Ember.Controller.extend({
		init: function() {
		}
	});

	App.BreadcrumbMenuView = Ember.View.extend({
		classNames: ['pull-right'],
		templateName: 'user_interface_breadcrumb_menu'
	});

}).call(this);