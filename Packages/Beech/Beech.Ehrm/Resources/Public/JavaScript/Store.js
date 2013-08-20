(function() {

App.Adapter = DS.RESTAdapter.extend({
	bulkCommit: false,
	namespace: 'rest'
});

DS.Adapter.configure('plurals', {
	'beech_party_domain_model_company': 'beech_party_domain_model_companies',
	'beech_party_domain_model_electronic_address': 'beech_party_domain_model_electronic_addresses',
	'beech_party_domain_model_address': 'beech_party_domain_model_addresses',
});

App.Store = DS.Store.extend({
	adapter: 'App.Adapter'
});

})();