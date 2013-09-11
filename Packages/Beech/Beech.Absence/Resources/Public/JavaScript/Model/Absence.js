(function() {
	'use strict';

	App.BeechAbsenceDomainModelAbsence.reopen({
		department: DS.belongsTo("App.BeechPartyDomainModelCompany"),
		absenceArrangement: DS.belongsTo("App.BeechAbsenceDomainModelAbsenceArrangement"),
		person: DS.belongsTo("App.BeechPartyDomainModelPerson"),
		reportedTo: DS.belongsTo("App.BeechPartyDomainModelPerson"),
		absenceType: DS.attr('string')
	});

	App.BeechAbsenceDomainModelAbsenceArrangement.reopen({
		absence: DS.attr("string"),
	});

}).call(this);