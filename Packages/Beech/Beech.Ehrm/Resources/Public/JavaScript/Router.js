define(
	[
		'jquery',
		'emberjs',
		MM.configuration.routerConfigurationUrl
	],
	function ($, Ember) {
		MM.init.preInitialize.push(function() {
			App.Router = App.Router.reopen({
				enableLogging: true
			});
		});
	}
);