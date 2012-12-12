define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.DashboardWidgetView = App.DashboardWidgetView.reopen({
				classNames: ['span10']
			});
		});
	}
);