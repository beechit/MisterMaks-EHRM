MM.init.preInitialize.push(function() {
	'use strict';

	App.Router = App.Router.reopen({
		enableLogging: true
	});
});