(function() {
	'use strict';

	App.BeechTaskDomainModelTask.reopen({
		assignedTo: DS.belongsTo("App.BeechPartyDomainModelPerson"),
		createdBy: DS.belongsTo("App.BeechPartyDomainModelPerson"),
		closedBy: DS.belongsTo("App.BeechPartyDomainModelPerson"),
		action: DS.attr("string"),
		escalatedTask: DS.attr("string")
	});

}).call(this);