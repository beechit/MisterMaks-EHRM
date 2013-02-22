(function() {
	'use strict';

	App.CompaniesRoute = Ember.Route.extend({
		renderTemplate: function() {
			this.render('user_interface_breadcrumb_menu', { outlet: 'breadcrumbMenu', controller: 'breadcrumbMenu' });
			this.render('user_interface_user_menu', { outlet: 'userMenu' });
			this.render('administration_menu', { outlet: 'sidebar' });
			this.render('companies', {outlet: 'main'});
		},
		model: function() {
			return App.Company.find();
		}

	});

	App.CompanyRoute = Ember.Route.extend({
		renderTemplate: function() {
			this.render('user_interface_breadcrumb_menu', { outlet: 'breadcrumbMenu', controller: 'breadcrumbMenu' });
			this.render('user_interface_user_menu', { outlet: 'userMenu' });
			this.render('administration_menu', { outlet: 'sidebar' });
			this.render('company', {outlet: 'main'});
		},
		model: function(params) {
			return App.Company.find(params.company_id);
		}
	});

	App.NewCompanyRoute = Ember.Route.extend({
		renderTemplate: function() {
			this.render('user_interface_breadcrumb_menu', { outlet: 'breadcrumbMenu', controller: 'breadcrumbMenu' });
			this.render('user_interface_user_menu', { outlet: 'userMenu' });
			this.render('administration_menu', { outlet: 'sidebar' });
			this.render('edit_company', {controller: 'new_company'});
		},
		model: function() {
			return App.Company.createRecord();
		},
		exit: function() {
			var model = this.get('controller.model');
			if (!model.get('isSaving')) {
				model.deleteRecord();
			}
		}

	});

}).call(this);