(function() {
	'use strict';

	App.BeechAbsenceAbsencesController = Ember.ObjectController.extend({
		departments: [],
		absences: [],
		selectedDepartment: null,
		startDate: '',
		intervalInDays: 28,
		init: function() {
			this.set('departments',this.get('store').find('beechPartyDomainModelCompany'));
		},
		selectionChanged: function() {
			this.openDepartmentOverview();
		}.observes('selectedDepartment'),
		startDateChanged: function() {
			this.openDepartmentOverview();
		}.observes('startDate'),
		openDepartmentOverview: function() {
			if (this.get('selectedDepartment') && this.get('selectedDepartment').get('id') && this.get('startDate')) {
				this.transitionToRoute('BeechAbsenceAbsences.department', {
					departmentId: this.get('selectedDepartment').get('id'),
					startDate: this.get('startDate')
				});
			}
		},
		next: function(){
			this.set('startDate', moment(new Date(this.get('startDate'))).add('days', this.get('intervalInDays')).format('YYYY-MM-DD'));
		},
		prev: function() {
			this.set('startDate', moment(new Date(this.get('startDate'))).subtract('days', this.get('intervalInDays')).format('YYYY-MM-DD'));
		}
	});

	App.BeechAbsenceAbsencesDepartmentController =  Ember.ObjectController.extend({
		needs: ['BeechAbsenceAbsences'],
		absences: [],
		department: null,
		startDate: '',
		intervalInDays: 7,
		actions: {
			next: function() {
				this.get('controllers.BeechAbsenceAbsences').next();
			},
			prev: function() {
				this.get('controllers.BeechAbsenceAbsences').prev();
			}
		},
		dates: function () {
			if (!this.get('startDate')) {
				return [];
			}

			var curr = moment(new Date(this.get('startDate'))),
				dates = [], i, interval = this.get('intervalInDays');

			for (i = 0; i < interval; i++) {
				dates.push(curr.clone().add('days', i));
			}

			return dates;
		}.property('department', 'startDate', 'intervalInDays', 'absences.isLoaded')
	});

}).call(this);