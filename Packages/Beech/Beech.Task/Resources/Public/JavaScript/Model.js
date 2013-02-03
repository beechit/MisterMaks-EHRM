(function() {
	'use strict';

	App.Priority = DS.Model.extend({
		label: DS.attr('string'),
		tasks: DS.hasMany('App.Task'),
		didLoad: function() {
			console.log('Priority: '+ this.get('label') +' finished loading.');
		}
	});

	App.store.loadMany(App.Priority, [
		{ id: 1, label: 'Low' },
		{ id: 2, label: 'Normal' },
		{ id: 3, label: 'High' },
		{ id: 4, label: 'Immediate' }
	]);

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
