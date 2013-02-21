(function() {
	'use strict';

	App.Task = DS.Model.extend(App.ModelMixin, {
		assignedTo: DS.attr('string'),
		canBeClosedByCurrentParty: DS.attr('boolean'),
		closeDateTime: DS.attr('string'),
		closeableByAssignee: DS.attr('boolean'),
		closed: DS.attr('boolean'),
		closedBy: DS.attr('string'),
		creationDateTime: DS.attr('string'),
		description: DS.attr('string'),
		priority: DS.belongsTo('App.Priority')
	});

}).call(this);
