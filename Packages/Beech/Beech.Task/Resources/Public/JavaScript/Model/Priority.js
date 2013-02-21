(function() {
	'use strict';

	App.Priority = DS.Model.extend({
		label: DS.attr('string'),
		tasks: DS.hasMany('App.Task')
	});

}).call(this);
