(function() {
	'use strict';

	App.PersonsAdministrationRoute = App.AdministrationRoute.extend({
		templateName: 'administration_person_index',

		model: function() {
			return App.Person.find();
		}

	});

	App.PersonAdministrationRoute = App.AdministrationRoute.extend({
		templateName: 'administration_person_show',

		model: function() {
			return App.Person.find();
		}

	});

	App.PersonAdministrationEditRoute = App.AdministrationRoute.extend({
		templateName: 'administration_person_edit',

		model: function() {
			return App.Person.find();
		}

	});

	App.PersonAdministrationNewRoute = App.AdministrationRoute.extend({
		templateName: 'administration_person_new',

		model: function() {
			return App.Person.find();
		}

	});

}).call(this);