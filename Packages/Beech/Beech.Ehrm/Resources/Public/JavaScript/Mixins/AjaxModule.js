(function() {
	'use strict';


	App.AjaxModuleViewMixin = Ember.Mixin.create({
		templateName: 'jsonp_module',
		url: '',
		didInsertElement: function() {
			App.ModuleHandler.loadUrl(this.get('url'));
		}
	});

	App.AjaxModuleControllerMixin = Ember.Mixin.create({

		url: '',

		renderTemplate: function() {
			var that = this;

			this._super();
			this.render('jsonp_module');

			if (this.get('url') == '') {
				return;
			}

			setTimeout(function() {
				App.ModuleHandler.loadUrl(that.get('url'));
			}, 10);
		}
	});

}).call(this);