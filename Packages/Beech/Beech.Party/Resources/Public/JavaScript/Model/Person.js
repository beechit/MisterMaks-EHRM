(function() {
	'use strict';

	App.Typo3PartyDomainModelPersonName.reopen({
		person: DS.belongsTo('App.BeechPartyDomainModelPerson'),
		fullName: function() {
			var fullName = this.get('firstName');
			if (this.get('middleName')) {
				fullName = fullName + ' ' + this.get('middleName');
			}
			return fullName + ' ' + this.get('lastName');
		}.property('firstName', 'middleName', 'lastName')
	});

	App.BeechPartyDomainModelElectronicAdress.reopen({
		person: DS.belongsTo('App.BeechPartyDomainModelPerson')
	});

	App.BeechPartyDomainModelPerson.reopen({
		name: DS.belongsTo('App.Typo3PartyDomainModelPersonName'),
		electronicAddresses: DS.hasMany('App.BeechPartyDomainModelElectronicAdress'),
		primaryElectronicAddresses: DS.belongsTo('App.BeechPartyDomainModelElectronicAdress'),
		primaryElectronicAddress: DS.attr('string'),
		fullName: function() {
			return this.get('name').get('fullName');
		}.property()
	});

}).call(this);