App = Ember.Application.create({
	autoinit: false
});

define = function() {
	for (var i in arguments) {
		if (typeof arguments[i] === 'function') {
			MM.init.preInitialize.push(arguments[i]);
			return;
		}
	}
};

$(document).ready(function () {
	if (MM.authenticated) {
		if (MM.init.onLoad) {
			for (var i in MM.init.onLoad) {
				if (i.match(/^[0-9]*$/)) {
					MM.init.onLoad[i].call();
				}
			}
		}

		// TODO: Clean this up if we are not gonna use this anymore

		if (MM.init.preInitialize) {
			for (var i in MM.init.preInitialize) {
				if (i.match(/^[0-9]*$/)) {
					MM.init.preInitialize[i].call();
				}
			}
		}

		App.initialize();

		if (MM.init.afterInitialize) {
			for (var i in MM.init.afterInitialize) {
				if (i.match(/^[0-9]*$/)) {
					MM.init.afterInitialize[i].call();
				}
			}
		}
	}
});