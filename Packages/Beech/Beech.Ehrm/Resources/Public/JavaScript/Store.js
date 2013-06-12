App.RESTAdapter = DS.RESTAdapter.extend({
});

App.RESTAdapter.configure('plurals', {
	'beech_party_domain_model_company': 'beech_party_domain_model_companies',
	'beech_party_domain_model_electronic_address': 'beech_party_domain_model_electronic_addresses',
	'beech_party_domain_model_address': 'beech_party_domain_model_addresses',
});

App.store = DS.Store.create({
	revision: 11,
	adapter: App.RESTAdapter.create({
		bulkCommit: false,
		namespace: 'rest'
	})
});