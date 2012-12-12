define(
	[
		'emberjs',
		MM.configuration.mvcConfigurationUrl
	],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.ApplicationController = App.ApplicationController.reopen({
				title: 'Mister Maks'
			});
		});
	}
);