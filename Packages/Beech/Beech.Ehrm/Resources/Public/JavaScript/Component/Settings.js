define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.SettingsController = Ember.Controller.extend({
				content: 'Foo'
			});
			App.SettingsView = Ember.View.extend({
				templateName: 'settings'
			});
		});
	}
);