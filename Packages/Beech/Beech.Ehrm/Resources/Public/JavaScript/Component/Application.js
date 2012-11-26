define(
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.ApplicationController = Ember.Controller.extend({
				title: 'Mister Maks'
			});
			App.ApplicationView = Ember.View.extend({
				templateName: 'application'
			});
		});
	}
);