define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.SettingsController = App.SettingsController.reopen({
				content: 'Foo'
			});
		});
	}
);