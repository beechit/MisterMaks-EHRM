(function() {
	'use strict';

	App.BeechAbsenceAbsencesRoute = Ember.Route.extend({
		model: function() {
			return this.get('store').findAll('beechAbsenceDomainModelAbsence');
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
			var _this = this, _controller = controller;
			this.get('store').find('beechPartyDomainModelCompany', model.departmentId).then(function(department){
				// set controls in parent controller
				_this.controllerFor('BeechAbsenceAbsences').set('selectedDepartment',department);
			});

			this.controllerFor('BeechAbsenceAbsences').set('startDate', model.startDate);

		}
	});

}).call(this);