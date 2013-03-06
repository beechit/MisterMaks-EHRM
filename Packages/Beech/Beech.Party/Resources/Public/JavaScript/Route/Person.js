(function() {
	'use strict';

	App.BeechPartyPersonModuleIndexController = Ember.ArrayController.extend();
	App.BeechPartyPersonModuleIndexRoute = App.ModuleRoute.extend({
		model: function() {
			return App.BeechPartyDomainModelPerson.find();
		}
	});

	App.BeechPartyDomainModelPersonEditRoute = Ember.Route.extend(App.ModelFormableMixin, {
		redirectToRouteName: 'BeechPartyPersonModule.index',
		formTemplateName: 'BeechPartyDomainModelPerson/form',
		model: function() {
			return this.modelFor('BeechPartyDomainModelPerson');
		}
	});

		// Administration
	App.BeechPartyPersonAdministrationModuleIndexController = Ember.ArrayController.extend();
	App.BeechPartyPersonAdministrationModuleIndexRoute = App.ModuleRoute.extend({
		renderTemplate: function() {
			this.render('administration_menu', { outlet: 'sidebar' });
			this._super.apply(this, arguments);
		},
		model: function() {
			return App.BeechPartyDomainModelPerson.find();
		}
	});

	App.BeechPartyPersonAdministrationModuleNewRoute = Ember.Route.extend(App.ModelFormableMixin, {
		redirectToRouteName: 'BeechPartyPersonAdministrationModule.index',
		formTemplateName: 'BeechPartyDomainModelPersonAdministration/form',
		model: function() {
			return App.BeechPartyDomainModelPerson.createRecord();
		}
	});

	App.BeechPartyDomainModelPersonAdministrationEditRoute = Ember.Route.extend(App.ModelFormableMixin, {
		redirectToRouteName: 'BeechPartyPersonAdministrationModule.index',
		formTemplateName: 'BeechPartyDomainModelPersonAdministration/form',
		model: function() {
			return this.modelFor('BeechPartyDomainModelPerson');
		}
	});

}).call(this);