(function() {
	'use strict';

	App.ModelFormableMixin = Ember.Mixin.create({
		redirectToRouteName: null,
		formTemplateName: null,
		renderTemplate: function() {
			return this.render(
				this.get('formTemplateName'),
				{ outlet: 'main' }
			);
		},
		events: {
			cancel: function(model) {
				model.transaction.rollback();
				return this.transitionTo(this.get('redirectToRouteName'));
			},
			submit: function(content) {
				content.get('store').commit();
				return this.transitionTo(this.get('redirectToRouteName'));
			}
		}
	});
}).call(this);