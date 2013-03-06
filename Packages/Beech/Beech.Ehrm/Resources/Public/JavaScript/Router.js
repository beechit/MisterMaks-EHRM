(function() {
	'use strict';

	App.Router.map(function() {
		this.route('userSettings', { path: '/user/settings' });
		this.route('documentModule', { path: '/documents' });

			// Administration
		this.route('administration');
		this.route('applicationSettings', { path: '/administration/settings' });
		this.route('jobDescriptionModule', { path: '/administration/jobdescriptions' });
		this.route('contractArticleModule', { path: '/administration/contractarticles' });
		this.route('userManagementModule', { path: '/administration/usermanagement' });
		this.route('contractModule', { path: '/administration/contracts' });
		this.route('wizardManagementModule', { path: '/administration/wizards' });

			// Beech.Party
		this.resource('BeechPartyPersonModule', { path: '/persons' }, function() {
			this.resource('BeechPartyDomainModelPerson', { path: '/:beech_party_domain_model_person_id' }, function() {
				this.route('edit');
			});
		});
		this.resource('BeechPartyPersonAdministrationModule', { path: '/administration/persons' }, function() {
			this.route('new');
			this.resource('BeechPartyDomainModelPersonAdministration', { path: '/:beech_party_domain_model_person_id' }, function() {
				this.route('edit');
			});
		});

		this.resource('BeechPartyCompanyModule', { path: '/companies' }, function() {
			this.resource('BeechPartyDomainModelCompany', { path: '/:beech_party_domain_model_company_id' }, function() {
				this.route('edit');
			});
		});
		this.resource('BeechPartyCompanyAdministrationModule', { path: '/administration/companies' }, function() {
			this.route('new');
			this.resource('BeechPartyDomainModelCompanyAdministration', { path: '/:beech_party_domain_model_company_id' }, function() {
				this.route('edit');
			});
		});

			// Beech.Task
		this.resource('BeechTaskTaskModule', { path: '/tasks' }, function() {
			this.route('new');
			this.resource('BeechTaskDomainModelTask', { path: '/:beech_task_domain_model_task_id' }, function() {
				this.route('edit');
			});
		});
	});

	App.BaseRouteMixin = Ember.Mixin.create({
		renderTemplate: function() {
			this.render('beech_task_user_interface_task_widget', { outlet: 'sidebar', controller: 'taskWidget' });
		}
	});

	App.ModuleRoute = Ember.Route.extend(App.BaseRouteMixin, {
		renderTemplate: function() {
			this._super.apply(this, arguments);
			this.render(this.get('routeName').replace('.', '/'));
		}
	});

	App.IndexRoute = Ember.Route.extend(App.BaseRouteMixin, {
		renderTemplate: function() {
			this._super.apply(this, arguments);
			this.render('user_interface_dashboard');
		}
	});

		// TODO: Replace AJAX module loading by full ember / ember-data modules
	App.DocumentModuleRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.documents });
	App.UserSettingsRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.userSettings });
	App.JobDescriptionModuleRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.jobDescription });
	App.ContractArticleModuleRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.contractArticle });
	App.ContractModuleRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.contract });
	App.UserManagementModuleRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.userManagementModule });
	App.WizardManagementModuleRoute = App.IndexRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.wizardManagementModule });

		// Administration routes
	App.AdministrationRoute = Ember.Route.extend({
		templatePrefix: 'administration_',

		renderTemplate: function() {
			this.render('administration_menu', { outlet: 'sidebar' });
			this.render(this.get('templateName'));
		}
	});

	App.ApplicationSettingsRoute = App.AdministrationRoute.extend(App.AjaxModuleControllerMixin, { url: MM.url.module.applicationSettings });

}).call(this);