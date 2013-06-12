(function() {
	'use strict';

	App.BeechTaskTaskWidgetController = Ember.ArrayController.extend({
		// dummy data
		content: [{id:'1111',label:'Immediate', tasks:[]},{id:'222',label:'High',tasks:[]}],

		init: function() {
		}
	});

}).call(this);