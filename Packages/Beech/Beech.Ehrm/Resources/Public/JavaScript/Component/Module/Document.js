define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.ModuleDocumentController = Ember.Controller.extend();
			App.ModuleDocumentView = Ember.View.extend({
				templateName: 'module-document'
			});
		});
	}
);