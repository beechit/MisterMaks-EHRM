(function() {
	'use strict';

	App.BeechPartyCompanyModuleIndexRoute = App.ModuleRoute.extend({
		model: function() {
			return App.BeechPartyDomainModelCompany.find();
		}
	});

	App.BeechPartyDomainModelCompanyIndexRoute = Ember.Route.extend(App.ModelFormableMixin, {
		model: function() {
			return this.modelFor('BeechPartyDomainModelCompany');
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
	App.BeechPartyCompanyAdministrationModuleIndexRoute = App.ModuleRoute.extend({
		renderTemplate: function() {
			this._super.apply(this, arguments);
			this.render('administration_menu', { outlet: 'sidebar' });
		},
		model: function() {
			return App.BeechPartyDomainModelCompany.find();
		}
	});

	App.BeechPartyDomainModelCompanyAdministrationIndexRoute = Ember.Route.extend(App.ModelFormableMixin, {
		model: function() {
			return this.modelFor('BeechPartyDomainModelCompanyAdministration');
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
			return this.modelFor('BeechPartyDomainModelCompanyAdministration');
		}
	});

}).call(this);