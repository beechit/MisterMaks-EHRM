(function() {
	'use strict';

	App.CompanyController = Ember.ObjectController.extend({

		destroy: function() {
			if (!confirm('Are you sure?')) return;
			this.get('model').deleteRecord();
			App.store.commit();
			this.get('target').transitionTo('companies');
		}

	});

	App.EditCompanyController = Ember.ObjectController.extend({

		save: function() {
			App.store.commit();
			this.redirectToModel();
		},

		redirectToModel: function() {
			var router = this.get('target');
			var model = this.get('model');
			router.transitionTo('company', model);
		}

	});

	App.NewCompanyController = App.EditCompanyController.extend();

}).call(this);