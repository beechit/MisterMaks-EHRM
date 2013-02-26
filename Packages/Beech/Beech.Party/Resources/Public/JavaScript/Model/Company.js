(function() {
	'use strict';

	App.Company = DS.Model.extend(App.ModelMixin,{
		name: DS.attr('string'),
			// TODO relationship
		departments: DS.attr('string'),
		chamberOfCommerceNumber: DS.attr('string'),
			// TODO relationship
		accounts: DS.attr('string'),
		companyType: DS.attr('string'),
		companyIdNumber: DS.attr('number'),
		policyNumberAbsenceInsurer: DS.attr('string'),
		legalForm: DS.attr('string'),
		vatNumber: DS.attr('string'),
		wageTaxNumber: DS.attr('string'),
		companyDescription: DS.attr('string'),
		creationDate: DS.attr('string'),
		createdBy: DS.attr('string'),
		modification: DS.attr('string')
	});

}).call(this);