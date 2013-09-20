(function() {
	'use strict';

	App.BeechTaskTaskWidgetController = Ember.ArrayController.extend({
		content: [],
		initialLoadFinished: false,
		init: function() {
			var that = this;
			this.get('store').findQuery('beechTaskDomainModelTask', {ownTasks:true}).then(function(){
				that.set('initialLoadFinished', true);
			});
			this.set('content', this.get('store').filter('beechTaskDomainModelTask', function(task) {
				return task.get('closed') == false;
			}));
		},
		isLoaded: function() {
			if(this.get('initialLoadFinished')) {
				return true;
			} else {
				return false;
			}
		}.property('initialLoadFinished'),
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
		}.property('content.@each.closed', 'content.@each.priority', 'content.@each.id', 'content.@each.description'),
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
		}.property('content.@each.closed', 'content.@each.priority', 'content.@each.id', 'content.@each.description'),
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
		}.property('content.@each.closed', 'content.@each.priority', 'content.@each.id', 'content.@each.description'),
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
		}.property('content.@each.closed', 'content.@each.priority', 'content.@each.id', 'content.@each.description'),
	});

}).call(this);