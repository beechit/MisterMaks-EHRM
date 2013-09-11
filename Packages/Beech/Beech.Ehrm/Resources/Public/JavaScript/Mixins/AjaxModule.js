(function() {
	'use strict';

	App.AjaxModuleViewMixin = Ember.Mixin.create({
		template: Ember.Handlebars.compile('<div class="ehrm-module"><p><i class="icon-spin icon-spinner icon-3x muted"></i></p></div>')
	});

}).call(this);