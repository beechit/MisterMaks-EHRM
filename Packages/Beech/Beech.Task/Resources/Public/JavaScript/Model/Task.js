(function() {
	'use strict';

	App.BeechTaskDomainModelTask.reopen({
		assignedTo: DS.attr('string'),
		createdBy: DS.attr('string'),
		closedBy: DS.attr('string')
	});

}).call(this);