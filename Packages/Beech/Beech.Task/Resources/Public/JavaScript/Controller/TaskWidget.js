(function() {
	'use strict';

	App.BeechTaskTaskWidgetController = Ember.ArrayController.extend({
		content: [],
		loaded: false,
		init: function() {
			this.set('content', App.BeechTaskDomainModelTask.find({ownTasks:true}));
		},
		isLoaded: function() {
			if(this.get('content').get('isLoaded') && !this.get('content').get('isUpdating')) {
				return true;
			} else {
				return false;
			}
		}.property('content.isLoaded').property('content.isUpdating').property('content.@each.id'),
		countOpenTasks: function() {
			return this.get('content').filterProperty('closed', false).length;
		}.property('content.@each.closed'),
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
			return tasks;
		}.property('content.@each.closed').property('content.@each.priority')
	});

}).call(this);