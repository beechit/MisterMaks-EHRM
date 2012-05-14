var myapp = {};

myapp.Greeter = function() { };

myapp.Greeter.prototype.greet = function(name) {
	'use strict';
	if (name) {
		return "Hello " + name + "!";
	}
	return null;
};
