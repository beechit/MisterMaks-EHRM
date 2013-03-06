(function() {
	'use strict';

	App.BeechPartyCompanyModuleIndexController = Ember.ArrayController.extend();
	App.BeechPartyCompanyModuleIndexRoute = App.ModuleRoute.extend({
		model: function() {
			return App.BeechPartyDomainModelCompany.find();
		}
	});

	App.BeechPartyDomainModelCompanyEditRoute = Ember.Route.extend(App.ModelFormableMixin, {
		redirectToRouteName: 'BeechPartyCompanyModule.index',
		formTemplateName: 'BeechPartyDomainModelCompany/form',
		model: function() {
			return this.modelFor('BeechPartyDomainModelCompany');
		}
	});

		// Administration
	App.BeechPartyCompanyAdministrationModuleIndexController = Ember.ArrayController.extend();
	App.BeechPartyCompanyAdministrationModuleIndexRoute = App.ModuleRoute.extend({
		renderTemplate: function() {
			this.render('administration_menu', { outlet: 'sidebar' });
			this._super.apply(this, arguments);
		},
		model: function() {
			return App.BeechPartyDomainModelCompany.find();
		}
	});

	App.BeechPartyCompanyAdministrationModuleNewRoute = Ember.Route.extend(App.ModelFormableMixin, {
		redirectToRouteName: 'BeechPartyCompanyAdministrationModule.index',
		formTemplateName: 'BeechPartyDomainModelCompanyAdministration/form',
		model: function() {
			return App.BeechPartyDomainModelCompany.createRecord();
		}
	});

	App.BeechPartyDomainModelCompanyAdministrationEditRoute = Ember.Route.extend(App.ModelFormableMixin, {
		redirectToRouteName: 'BeechPartyCompanyAdministrationModule.index',
		formTemplateName: 'BeechPartyDomainModelCompanyAdministration/form',
		model: function() {
			return this.modelFor('BeechPartyDomainModelCompany');
		}
	});

}).call(this);