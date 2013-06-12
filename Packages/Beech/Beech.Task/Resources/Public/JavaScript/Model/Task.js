(function() {
	'use strict';

	App.BeechTaskDomainModelTask.reopen({
		assignedTo: DS.belongsTo("App.BeechPartyDomainModelPerson"),
		createdBy: DS.belongsTo("App.BeechPartyDomainModelPerson"),
		closedBy: DS.belongsTo("App.BeechPartyDomainModelPerson"),
		watchChildrenUpdate: function() {
			console.log('update')
		}.observes('prioriry')
	});

}).call(this);