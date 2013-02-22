App.store = DS.Store.create({
	revision: 11,
	adapter: DS.RESTAdapter.create({
		bulkCommit: false,
		namespace: 'rest'
	})
});