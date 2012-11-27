define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.AllTodosController = Ember.ArrayController.extend();
			App.AllTodosView = Ember.View.extend({
				classNames: ['well'],
				templateName: 'alltodos'
			});
		});
	}
);