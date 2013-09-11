(function() {
	'use strict';

	App.BeechAbsenceAbsencesRoute = Ember.Route.extend({
		model: function() {
			return this.get('store').find('BeechAbsenceDomainModelAbsence');
		},
		setupController:function(controller,model) {

			// reset selections you navigate directly to absence overview
			controller.set('selectedDepartment', null);
			if (!controller.get('startDate', '')) {
				controller.set('startDate', moment().format('YYYY-MM-DD'));
			}
			controller.set('absences', model);
		}
	});

	App.BeechAbsenceAbsencesDepartmentRoute = Ember.Route.extend({

		setupController:function(controller,model) {

			// no startdate given then we use today
			if (!model.startDate) {
				model.startDate = moment().format('YYYY-MM-DD');
			}

			// set controls in parent controller
			this.controllerFor('BeechAbsenceAbsences').set('selectedDepartment',this.get('store').find('beechPartyDomainModelCompany', model.departmentId));
			controller.set('department',this.get('store').find('beechPartyDomainModelCompany', model.departmentId));
			this.controllerFor('BeechAbsenceAbsences').set('startDate', model.startDate);
			controller.set('startDate', model.startDate);
			controller.set('intervalInDays', this.controllerFor('BeechAbsenceAbsences').get('intervalInDays'));
			controller.set('absences', this.controllerFor('BeechAbsenceAbsences').get('absences'));
		}
	});

}).call(this);