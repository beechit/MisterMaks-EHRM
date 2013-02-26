(function() {
	'use strict';

	App.BeechTaskDomainModelPriority.reopen({
		tasks: DS.hasMany('App.BeechTaskDomainModelTask')
	});

}).call(this);