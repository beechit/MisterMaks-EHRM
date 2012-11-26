define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.DashboardWidgetController = Ember.Controller.extend();
			App.DashboardWidgetView = Ember.View.extend({
				templateName: 'dashboardwidget'
			});
		});
	}
);