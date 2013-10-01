(function() {
	'use strict';

	App.BeechAbsenceAbsencesController = Ember.ObjectController.extend({
		departments: [],
		absences: [],
		init: function() {
			this.set('absences', this.get('store').findAll('beechAbsenceDomainModelAbsence'));
			this.set('departments',this.get('store').findAll('beechPartyDomainModelCompany'));
		}
	});

	App.BeechAbsenceAbsencesOverviewController =  Ember.ObjectController.extend({
		needs: ['BeechAbsenceAbsences', 'BeechAbsenceAbsencesOverviewOverview'],
		department: null,
		startDate: '',
		intervalInDays: 28,
		init: function() {
			this.set('startDate', moment().format('YYYY-MM-DD'));
		},
		openDepartmentOverview: function() {
			if (this.get('department') && this.get('department').get('id') && this.get('startDate')) {
				this.transitionToRoute('BeechAbsenceAbsencesOverview.overview', {
					department: this.get('department'),
					startDate: this.get('startDate')
				});
			}
		}.observes('department', 'startDate'),
		next: function(){
			this.set('startDate', moment(new Date(this.get('startDate'))).add('days', this.get('intervalInDays')).format('YYYY-MM-DD'));
		},
		prev: function() {
			this.set('startDate', moment(new Date(this.get('startDate'))).subtract('days', this.get('intervalInDays')).format('YYYY-MM-DD'));
		}
	});

	App.BeechAbsenceAbsencesOverviewOverviewController =  Ember.ObjectController.extend({
		needs: ['BeechAbsenceAbsences','BeechAbsenceAbsencesOverview'],
		department: null,
		startDate: null,
		actions: {
			next: function() {
				this.get('controllers.BeechAbsenceAbsencesOverview').next();
			},
			prev: function() {
				this.get('controllers.BeechAbsenceAbsencesOverview').prev();
			}
		},
		dates: function () {

			if (!this.get('startDate')) {
				return [];
			}
			var curr = moment(new Date(this.get('startDate'))),
				dates = [], i, interval = this.get('controllers.BeechAbsenceAbsencesOverview.intervalInDays');

			for (i = 0; i < interval; i++) {
				dates.push(curr.clone().add('days', i));
			}

			return dates;
		}.property('department','controllers.BeechAbsenceAbsences.absences', 'startDate', 'controllers.BeechAbsenceAbsencesOverview.intervalInDays')
	});


	App.BeechAbsenceAbsencesListController = Ember.ObjectController.extend({
		needs: ['BeechAbsenceAbsences'],
		department: null,
		//absenceType: '', //do not decleare is done by ember because of route param
		selectionChanged: function() {
			this.transitionToRoute('BeechAbsenceAbsencesList.list', this.get('department'));
		}.observes('department')
	});

	App.BeechAbsenceAbsencesListListController = Ember.ObjectController.extend({
		needs: ['BeechAbsenceAbsences', 'BeechAbsenceAbsencesList'],
		url: MM.url.module.BeechAbsenceAbsenceList,
		department: null,
		ajaxUrl: '',
		generateUrl: function() {
			if (this.get('department')) {
				this.set('ajaxUrl', this.get('url')+'&department='+this.get('department').get('id')+'&absenceType='+this.get('controllers.BeechAbsenceAbsencesList.absenceType'));
			}
		}.observes('department'),
		refresh: function() {
			if (this.get('ajaxUrl')) {
				this.set('ajaxUrl', this.ajaxUrl+'&');
			}
		}
	});

}).call(this);