(function () {
	'use strict';

	App.Typo3PartyDomainModelPersonName.reopen({
		person: DS.belongsTo('App.BeechPartyDomainModelPerson'),
		fullName: function () {
			var fullName = this.get('firstName');
			if (this.get('middleName')) {
				fullName = fullName + ' ' + this.get('middleName');
			}
			return fullName + ' ' + this.get('lastName');
		}.property('firstName', 'middleName', 'lastName')
	});

	App.BeechPartyDomainModelElectronicAddress.reopen({
		person: DS.belongsTo('App.BeechPartyDomainModelPerson'),
		identifier: DS.attr("string"),
		type: DS.attr("string"),
		usage: DS.attr("string")
	});

	App.BeechPartyDomainModelEducation.reopen({
		person: DS.belongsTo('App.BeechPartyDomainModelPerson'),
		name: DS.attr('string'),
		direction: DS.attr('string'),
		startDate: DS.attr('string'),
		endDate: DS.attr('string'),
		graduated: DS.attr('boolean')
	});

	App.BeechPartyDomainModelBankAccount.reopen({
		person: DS.belongsTo('App.BeechPartyDomainModelPerson'),
		accountType: DS.attr('string'),
		ibanNumber: DS.attr('string'),
		bankName: DS.attr('string'),
		residence: DS.attr('string'),
		giro: DS.attr('string'),
		primary: DS.attr('boolean')
	});

	App.BeechPartyDomainModelPerson.reopen({
		name: DS.belongsTo('App.Typo3PartyDomainModelPersonName'),
		electronicAddresses: DS.hasMany('App.BeechPartyDomainModelElectronicAddress'),
		primaryElectronicAddresses: DS.belongsTo('App.BeechPartyDomainModelElectronicAddress'),
		primaryElectronicAddress: DS.attr('string'),
		phoneNumbers: DS.hasMany('App.BeechPartyDomainModelPhoneNumber'),
		addresses: DS.hasMany('App.BeechPartyDomainModelAddress'),
		educations: DS.hasMany('App.BeechPartyDomainModelEducation'),
		bankAccounts: DS.hasMany('App.BeechPartyDomainModelBankAccount'),
		fullName: function () {
			return this.get('name').get('fullName');
		}.property('name.firstName', 'name.middleName', 'name.lastName')
	});

	App.BeechPartyDomainModelPhoneNumber.reopen({
		person: DS.belongsTo('App.BeechPartyDomainModelPerson'),
		number: DS.attr('string')
	});

	App.BeechPartyDomainModelAddress.reopen({
		person: DS.belongsTo('App.BeechPartyDomainModelPerson'),
		streetName: DS.attr('string'),
		houseNumber: DS.attr('number'),
		addition: DS.attr('string'),
		postal: DS.attr('string'),
		residence: DS.attr('string'),
		country: DS.attr('string')
	});

}).call(this);