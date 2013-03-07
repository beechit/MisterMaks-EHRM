(function() {
	'use strict';

	App.BeechPartyDomainModelPerson.reopen({
		name: DS.attr('string'),
		electronicAddresses: DS.attr('string'),
		primaryElectronicAddress: DS.attr('string')
	});

}).call(this);