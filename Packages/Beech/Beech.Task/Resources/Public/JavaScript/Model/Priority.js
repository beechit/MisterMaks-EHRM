(function() {
	'use strict';

	App.BeechTaskDomainModelPriority.reopen({

		label: DS.attr('string'),
		tasks: DS.hasMany('App.BeechTaskDomainModelTask')
	});

}).call(this);