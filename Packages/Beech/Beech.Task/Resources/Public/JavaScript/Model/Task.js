(function() {
	'use strict';

	App.BeechTaskDomainModelTask.reopen({
		link: DS.attr("string"),
		assignedTo: DS.belongsTo("beechPartyDomainModelPerson"),
		createdBy: DS.belongsTo("beechPartyDomainModelPerson"),
		closedBy: DS.belongsTo("beechPartyDomainModelPerson"),
		action: DS.attr("string"),
		escalatedTask: DS.attr("string")
	});

}).call(this);