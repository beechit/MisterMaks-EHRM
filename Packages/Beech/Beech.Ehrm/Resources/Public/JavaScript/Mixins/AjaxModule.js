(function() {
	'use strict';

	App.AjaxViewMixin = Ember.Mixin.create({
		elementClass: '.ajax-outlet',
		visible: false,
		didInsertElement:function() {
			this.set('visible', true);
		},
		load: function() {
			if (this.visible && this.get('controller').get('ajaxUrl')) {
				App.ModuleHandler.loadUrl(this.get('controller').get('ajaxUrl'), this.$(this.get('elementClass')));
			}
		}.observes('controller.ajaxUrl', 'visible')
	});

	App.AjaxModuleViewMixin = Ember.Mixin.create({
		template: Ember.Handlebars.compile('<div class="ehrm-module"><p><i class="icon-spin icon-spinner icon-3x muted"></i></p></div>')
	});

}).call(this);