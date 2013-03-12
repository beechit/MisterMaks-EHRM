(function() {
	'use strict';
	App.BeechPartyCompanyModuleIndexView = Ember.View.extend({
		templateName: 'beech_party_domain_model_company/index'
	});
	App.BeechPartyDomainModelCompanyIndexView = Ember.View.extend({
		templateName: 'beech_party_domain_model_company/show'
	});

	App.BeechPartyCompanyAdministrationModuleIndexView = Ember.View.extend({
		templateName: 'beech_party_domain_model_company_administration/index'
	});
	App.BeechPartyDomainModelCompanyAdministrationIndexView = Ember.View.extend({
		templateName: 'beech_party_domain_model_company_administration/show'
	});
}).call(this);