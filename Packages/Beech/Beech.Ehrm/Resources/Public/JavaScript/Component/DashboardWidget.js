define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.DashboardWidgetController = Ember.Controller.extend();
			App.DashboardWidgetView = Ember.View.extend({
				classNames: ['span10'],
				templateName: 'dashboardwidget'
			});
		});
	}
);