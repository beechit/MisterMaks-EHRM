(function() {
	'use strict';

	App.Person = DS.Model.extend(App.ModelMixin, {
		name: DS.attr('string')
	});

}).call(this);