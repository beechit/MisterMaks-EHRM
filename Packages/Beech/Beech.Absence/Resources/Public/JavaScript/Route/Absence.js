(function() {
	'use strict';

	App.BeechAbsenceAbsencesListRoute = Ember.Route.extend({
		activate: function() {
			// redirect on re-entering
			var department = this.controllerFor('BeechAbsenceAbsencesList').get('department');
			if (department) {
				Ember.run.once(this, function () {
					this.transitionTo('BeechAbsenceAbsencesList.list', department);
				});
			}
		}
	});

	App.BeechAbsenceAbsencesListListRoute = Ember.Route.extend({
		model: function(params) {
			return this.get('store').find('beechPartyDomainModelCompany', params.department_id);
		},
		setupController: function(controller, model) {
			// keep parent in sync (direct url request)
			this.controllerFor('BeechAbsenceAbsencesList').set('department', model);
			controller.set('department', model);
		}
	});

	App.BeechAbsenceAbsencesOverviewRoute = Ember.Route.extend({
		activate: function() {
			// redirect on re-entering
			Ember.run.once(this, function () {
				this.controllerFor('BeechAbsenceAbsencesOverview').openDepartmentOverview();
			});
		}
	});

	App.BeechAbsenceAbsencesOverviewOverviewRoute = Ember.Route.extend({

		model: function(params) {
			var parentController = this.controllerFor('BeechAbsenceAbsencesOverview');

			if (params.department && params.department != 'undefined') {
				this.get('store').find('beechPartyDomainModelCompany', params.department).then(function(department){
					parentController.set('department', department);
				});
			}
			if (params.startDate && params.startDate != 'undefined') {
				parentController.set('startDate', params.startDate);
			}
			return {};
		},
		setupController:function(controller,model) {

			if (model && model.startDate) {
				controller.set('startDate', model.startDate);
			}

			// the selectbox in parent view can not handle promisses
			// so we handle the promisse and set it by hand
			if (typeof model.department == 'string') {
				var _controller = controller, _this = this;
				this.get('store').find('beechPartyDomainModelCompany', model.department).then(function(department) {
					_controller.set('department', department);
					_this.controllerFor('BeechAbsenceAbsencesOverview').set('department', department);
				});
			}
		},
		serialize: function(model, params) {
			if (!model) model = {department: '', startDate: ''}
			if (model.department && model.department != 'undefined') {
				model.department = model.department.get('id')
			}
			return model;
		}
	});

}).call(this);