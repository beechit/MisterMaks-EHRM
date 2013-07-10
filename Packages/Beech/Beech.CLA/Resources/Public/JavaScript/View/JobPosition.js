(function() {
	'use strict';

	App.BeechCLAJobPositionView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: '',
		didInsertElement: function() {

			App.ModuleHandler.loadUrl(this.get('url'), false, function() {
				console.log('callback');
			});
		}
	});

	}).call(this);