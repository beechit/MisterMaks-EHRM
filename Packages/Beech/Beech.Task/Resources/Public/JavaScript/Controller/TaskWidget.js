(function() {
	'use strict';

	App.BeechTaskTaskWidgetController = Ember.ArrayController.extend({
		content: [],
		init: function() {
			this.set('content', App.BeechTaskDomainModelTask.find());
		},
		openTasksLow: function() {
			var tasks = this.get('content');
			tasks = tasks.filterProperty('closed', false).filter(function(e,i,em){
				return (e && (e.get('priority') == 0 || !e.get('priority') || e.get('priority') > 3));
			}).sort(function(a, b){
				if(a.get('description') && b.get('description')) {
					var nameA=a.get('description').toLowerCase(), nameB=b.get('description').toLowerCase()
					if (nameA < nameB) //sort string ascending
						return -1
					if (nameA > nameB)
						return 1
				}
				return 0 //default return value (no sorting)
			});
			console.log('getTasks 0',tasks.length, this.get('content').get('length'));
			return tasks;
		}.property('content.@each.closed').property('content.@each.priority'),
		openTasksNormal: function() {
			var tasks = this.get('content');
			tasks = tasks.filterProperty('closed', false).filterProperty('priority',1).sort(function(a, b){
				if(a.get('description') && b.get('description')) {
					var nameA=a.get('description').toLowerCase(), nameB=b.get('description').toLowerCase()
					if (nameA < nameB) //sort string ascending
						return -1
					if (nameA > nameB)
						return 1
				}
				return 0 //default return value (no sorting)
			});
			console.log('getTasks 1',tasks.length, this.get('content').get('length'));
			return tasks;
		}.property('content.@each.closed').property('content.@each.priority'),
		openTasksHigh: function() {
			var tasks = this.get('content');
			tasks = tasks.filterProperty('closed', false).filterProperty('priority',2).sort(function(a, b){
				if(a.get('description') && b.get('description')) {
					var nameA=a.get('description').toLowerCase(), nameB=b.get('description').toLowerCase()
					if (nameA < nameB) //sort string ascending
						return -1
					if (nameA > nameB)
						return 1
				}
				return 0 //default return value (no sorting)
			});
			console.log('getTasks 2',tasks.length, this.get('content').get('length'));
			return tasks;
		}.property('content.@each.closed').property('content.@each.priority'),
		openTasksImmediate: function() {
			var tasks = this.get('content');
			tasks = tasks.filterProperty('closed', false).filterProperty('priority',3).sort(function(a, b){
				if(a.get('description') && b.get('description')) {
					var nameA=a.get('description').toLowerCase(), nameB=b.get('description').toLowerCase()
					if (nameA < nameB) //sort string ascending
						return -1
					if (nameA > nameB)
						return 1
				}
				return 0 //default return value (no sorting)
			});
			console.log('getTasks 3',tasks.length, this.get('content').get('length'));
			return tasks;
		}.property('content.@each.closed').property('content.@each.priority')
	});

}).call(this);