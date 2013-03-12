(function() {
	'use strict';

	App.BeechPartyDomainModelCompany.reopen({
		parent: DS.belongsTo('App.BeechPartyDomainModelCompany'),
		departments: DS.hasMany('App.BeechPartyDomainModelCompany')
	});

}).call(this);