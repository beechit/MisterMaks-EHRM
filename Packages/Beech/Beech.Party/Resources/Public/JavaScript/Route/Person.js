(function() {
	'use strict';

	App.BeechPartyPersonModuleIndexRoute = App.ModuleRoute.extend({
		model: function() {
			return App.BeechPartyDomainModelPerson.find();
		}
	});

	App.BeechPartyDomainModelPersonIndexRoute = Ember.Route.extend(App.ModelFormableMixin, {
		model: function() {
			return this.modelFor('BeechPartyDomainModelPerson');
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
	App.BeechPartyPersonAdministrationModuleIndexRoute = App.ModuleRoute.extend({
		renderTemplate: function() {
			this._super.apply(this, arguments);
			this.render('administration_menu', { outlet: 'sidebar' });
		},
		model: function() {
			return App.BeechPartyDomainModelPerson.find();
		}
	});

	App.BeechPartyDomainModelPersonAdministrationIndexRoute = Ember.Route.extend(App.ModelFormableMixin, {
		model: function() {
			return this.modelFor('BeechPartyDomainModelPersonAdministration');
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
			return this.modelFor('BeechPartyDomainModelPersonAdministration');
		}
	});

}).call(this);