define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.ManagementController = Ember.Controller.extend();
			App.ManagementView = Ember.View.extend({
				templateName: 'management'
			});
		});
	}
);