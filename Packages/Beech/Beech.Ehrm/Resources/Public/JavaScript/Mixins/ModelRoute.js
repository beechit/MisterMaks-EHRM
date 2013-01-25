(function() {
	'use strict';

	App.ModelRouteMixin = Ember.Mixin.create({
		templatePrefix: '',

		modelType: '',
		modelId: null,
		action: 'index',

		templateName: function() {
			var currentHandlerInfos = this.get('router.router.currentHandlerInfos');
			this.set('action', currentHandlerInfos[currentHandlerInfos.length - 1].name.replace(/.*\./, ''));
			return this.get('templatePrefix') + this.get('modelType') + '_' + this.get('action');
		}.property(),

		model: function(params) {
			if (params.id) {
				this.set('modelId', params.id);
			}
			if (params.type) {
				this.set('modelType', params.type);
			}

			return Ember.Object.create({id: 'foo'});
		}
	});
}).call(this);