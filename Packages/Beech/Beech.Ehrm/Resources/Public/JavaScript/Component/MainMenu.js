define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.MainMenu = Ember.View.extend({
				templateName: 'mainmenu'
			});
		});
	}
);