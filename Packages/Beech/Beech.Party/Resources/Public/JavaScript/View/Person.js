(function() {
	'use strict';
	App.BeechPartyPersonModuleIndexView = Ember.View.extend({
		templateName: 'beech_party_domain_model_person/index'
	});
	App.BeechPartyDomainModelPersonIndexView = Ember.View.extend({
		templateName: 'beech_party_domain_model_person/show'
	});

	App.BeechPartyPersonAdministrationModuleIndexView = Ember.View.extend({
		templateName: 'beech_party_domain_model_person_administration/index'
	});
	App.BeechPartyDomainModelPersonAdministrationIndexView = Ember.View.extend({
		templateName: 'beech_party_domain_model_person_administration/show'
	});
}).call(this);