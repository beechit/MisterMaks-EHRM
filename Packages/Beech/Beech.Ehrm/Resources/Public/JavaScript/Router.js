(function() {
	'use strict';

	App.Router.map(function() {
		this.route('userSettings', { path: '/user/settings' });
		this.route('applicationSettings', { path: '/administration/settings' });
		this.route('taskModule', { path: '/tasks' });
		this.route('jobDescriptionModule', { path: '/administration/jobdescriptions' });
		this.route('contractArticleModule', { path: '/administration/contractarticles' });
		this.route('documentModule', { path: '/documents' });

			// Administration mappings
		this.route('administration');
		this.route('userManagementModule', { path: '/administration/usermanagement' });
		this.resource('personAdministration', { path: '/administration/persons/' }, function() {
			this.resource('model', { path: '/:id' }, function() {
				this.route('edit');
			});
			this.route('new');
		});
		this.resource('companyAdministration', { path: '/administration/companies/' }, function() {
			this.resource('model', { path: '/:id' }, function() {
				this.route('edit');
			});
			this.route('new');
		});

			// Model mappings
		this.resource('person', { path: 'persons/' }, function() {
			this.resource('model', { path: '/:id' }, function() {
				this.route('edit');
			});
		});
	});

	App.IndexRoute = Ember.Route.extend({
		renderTemplate: function() {
			this.render('user_interface_breadcrumb_menu', { outlet: 'breadcrumbMenu', controller: 'breadcrumbMenu' });
			this.render('user_interface_user_menu', { outlet: 'userMenu' });
			this.render('user_interface_task_widget', { outlet: 'sidebar', controller: 'taskWidget' });
			this.render('user_interface_dashboard');
		}
	});
	App.PersonRoute = App.IndexRoute.extend();

		// TODO: Replace AJAX module loading by full ember / ember-data modules
	App.TaskModuleRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.tasks });
	App.DocumentModuleRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.documents });
	App.ApplicationSettingsRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.applicationSettings });
	App.UserSettingsRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.userSettings });
	App.JobDescriptionModuleRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.jobDescription });
	App.ContractArticleModuleRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.contractArticle });
	App.UserManagementModuleRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.userManagementModule });

		// Administration routes
	App.AdministrationRoute = Ember.Route.extend(App.ModelRouteMixin, {
		templatePrefix: 'administration_',

		renderTemplate: function() {
			this.render('user_interface_breadcrumb_menu', { outlet: 'breadcrumbMenu', controller: 'breadcrumbMenu' });
			this.render('user_interface_user_menu', { outlet: 'userMenu' });
			this.render('administration_menu', { outlet: 'sidebar' });
			this.render(this.get('templateName'));
		}
	});

	App.PersonAdministrationRoute = App.AdministrationRoute.extend({ modelType: 'person' });
	App.CompanyAdministrationRoute = App.AdministrationRoute.extend({ modelType: 'company' });

}).call(this);