App.RESTAdapter = DS.RESTAdapter.extend({
});

App.RESTAdapter.configure('plurals', {
	beech_task_domain_model_company: 'beech_task_domain_model_companies',
	beech_task_domain_model_priority: 'beech_task_domain_model_priorities'
});

App.store = DS.Store.create({
	revision: 11,
	adapter: App.RESTAdapter.create({
		bulkCommit: false,
		namespace: 'rest'
	})
});