(function() {
	'use strict';

	App.BeechPartyPersonModuleRoute = App.ModuleRoute.extend({
		model: function() {
			return App.BeechPartyDomainModelPerson.find();
		}
	});

	App.BeechPartyDomainModelPersonIndexRoute = Ember.Route.extend({
		model: function() {
			return this.modelFor('BeechPartyDomainModelPerson');
		}
	});

	App.BeechPartyDomainModelPersonEditRoute = Ember.Route.extend(App.ModelFormableMixin, {
		redirectToRouteName: 'BeechPartyPersonModule',
		formTemplateName: 'BeechPartyDomainModelPerson/form',
		model: function() {
			return this.modelFor('BeechPartyDomainModelPerson');
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
			removeEducation: function(obj) {
				this.model().get('educations').removeObject(obj);
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
	App.BeechPartyPersonAdministrationModuleIndexRoute = App.ModuleRoute.extend({
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