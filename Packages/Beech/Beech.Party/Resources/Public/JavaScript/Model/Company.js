(function() {
	'use strict';

	App.BeechPartyDomainModelCompany.reopen({
		parent: DS.belongsTo('App.BeechPartyDomainModelCompany'),
		departments: DS.hasMany('App.BeechPartyDomainModelCompany')
});

	App.BeechPartyDomainModelElectronicAddress.reopen({
		department: DS.belongsTo('App.BeechPartyDomainModelCompany'),
		identifier: DS.attr("string"),
		type: DS.attr("string"),
		usage: DS.attr("string"),
		primaryElectronicAddress: DS.attr('string')
	});

	App.BeechPartyDomainModelBankAccount.reopen({
		company: DS.belongsTo('App.BeechPartyDomainModelCompany'),
		department: DS.belongsTo('App.BeechPartyDomainModelCompany'),
		accountType: DS.attr('string'),
		ibanNumber: DS.attr('string'),
		bankName: DS.attr('string'),
		residence: DS.attr('string'),
		giro: DS.attr('string'),
		primary: DS.attr('boolean')
	});

	App.BeechPartyDomainModelCompany.reopen({
		electronicAddresses: DS.hasMany('App.BeechPartyDomainModelElectronicAddress'),
		phoneNumbers: DS.hasMany('App.BeechPartyDomainModelPhoneNumber'),
		addresses: DS.hasMany('App.BeechPartyDomainModelAddress'),
		bankAccounts: DS.hasMany('App.BeechPartyDomainModelBankAccount')
	});

	App.BeechPartyDomainModelPhoneNumber.reopen({
		company: DS.belongsTo('App.BeechPartyDomainModelCompany'),
		department: DS.belongsTo('App.BeechPartyDomainModelCompany'),
		number: DS.attr('string')
	});

	App.BeechPartyDomainModelAddress.reopen({
		company: DS.belongsTo('App.BeechPartyDomainModelCompany'),
		department: DS.belongsTo('App.BeechPartyDomainModelCompany'),
		streetName: DS.attr('string'),
		houseNumber: DS.attr('number'),
		addition: DS.attr('string'),
		postal: DS.attr('string'),
		residence: DS.attr('string'),
		country: DS.attr('string')
	});

}).call(this);