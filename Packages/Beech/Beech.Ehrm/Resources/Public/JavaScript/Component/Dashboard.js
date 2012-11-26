define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.DashboardController = Ember.Controller.extend();
			App.DashboardView = Ember.View.extend({
				templateName: 'dashboard'
			});
		});
	}
);