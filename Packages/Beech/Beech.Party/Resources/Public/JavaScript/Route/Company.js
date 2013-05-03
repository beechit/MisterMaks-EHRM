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
		},
			events: {
				cancel: function(model) {
					model.transaction.rollback();
					return this.transitionTo(this.get('redirectToRouteName'));
				},
				submit: function(content) {
					content.get('store').commit();
					return this.transitionTo(this.get('redirectToRouteName'));
				},
				addPhoneNumber: function() {
					this.model().get('phoneNumbers').pushObject(App.BeechPartyDomainModelPhoneNumber.createRecord());
				},
				removePhoneNumber: function(obj) {
					this.model().get('phoneNumbers').removeObject(obj);
				},
				addAddress: function() {
					this.model().get('addresses').pushObject(App.BeechPartyDomainModelAddress.createRecord());
				},
				removeAddress: function(obj) {
					this.model().get('addresses').removeObject(obj);
				},
				addElectronicAddress: function() {
					this.model().get('electronicAddresses').pushObject(App.BeechPartyDomainModelElectronicAddress.createRecord());
				},
				removeElectronicAddress: function(obj) {
					this.model().get('electronicAddresses').removeObject(obj);
				},
				addEducation: function() {
					this.model().get('educations').pushObject(App.BeechPartyDomainModelEducation.createRecord());
				},
				addBankAccount: function() {
					this.model().get('bankAccounts').pushObject(App.BeechPartyDomainModelBankAccount.createRecord());
				},
				removeBankAccount: function(obj) {
					this.model().get('bankAccounts').removeObject(obj);
				}
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