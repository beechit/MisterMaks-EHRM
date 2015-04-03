(function() {
	'use strict';

	App.BeechAbsenceDomainModelAbsence.reopen({
		department: DS.belongsTo("beechPartyDomainModelCompany"),
		absenceArrangement: DS.belongsTo("beechAbsenceDomainModelAbsenceArrangement"),
		person: DS.belongsTo("beechPartyDomainModelPerson"),
		reportedTo: DS.belongsTo("beechPartyDomainModelPerson"),
		absenceType: DS.attr('string')
	});

	App.BeechAbsenceDomainModelAbsenceArrangement.reopen({
		absence: DS.attr("string"),
	});

}).call(this);