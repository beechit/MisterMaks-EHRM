define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.ModuleTaskController = Ember.Controller.extend();
			App.ModuleTaskView = Ember.View.extend({
				templateName: 'moduletask'
			});
		});
	}
);