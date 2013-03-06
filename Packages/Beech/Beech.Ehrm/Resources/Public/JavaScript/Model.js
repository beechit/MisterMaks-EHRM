(function() {
	'use strict';

	// Use this file to add not yet existing models
	App.TYPO3PartyDomainModelPersonName = DS.Model.extend(App.ModelMixin, {
		title: DS.attr('string'),
		firstName: DS.attr('string'),
		middleName: DS.attr('string'),
		lastName: DS.attr('string'),
		otherName: DS.attr('string'),
		alias: DS.attr('string'),
		fullName: function() {
			var fullName = this.get('firstName');
			if (this.get('middleName')) {
				fullName = fullName + ' ' + this.get('middleName');
			}
			return fullName + ' ' + this.get('lastName');
		}.property('firstName', 'middleName', 'lastName')
	});

}).call(this);