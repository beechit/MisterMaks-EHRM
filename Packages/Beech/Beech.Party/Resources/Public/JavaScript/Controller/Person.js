(function() {
	'use strict';
	App.BeechPartyAdministrationPersonController = Ember.Controller.extend({
		url: '',
		loadUrl: function(params) {
			App.ModuleHandler.loadUrl(this.get('url') + params);
		}
	});
	App.BeechPartyAdministrationPersonIndexController = App.BeechPartyAdministrationPersonController.extend({
		url: MM.url.module.BeechPartyAdministrationPersonIndex
	});
	App.BeechPartyAdministrationPersonNewController = App.BeechPartyAdministrationPersonController.extend({
		url: MM.url.module.BeechPartyAdministrationPersonNew
	});
	App.BeechPartyAdministrationPersonShowController = App.BeechPartyAdministrationPersonController.extend({
		url: MM.url.module.BeechPartyAdministrationPersonShow
	});
	App.BeechPartyAdministrationPersonEditController = App.BeechPartyAdministrationPersonController.extend({
		url: MM.url.module.BeechPartyAdministrationPersonEdit
	});
	App.BeechPartyAdministrationPersonDeleteController = App.BeechPartyAdministrationPersonController.extend({
		url: MM.url.module.BeechPartyAdministrationPersonDelete
	});
}).call(this);