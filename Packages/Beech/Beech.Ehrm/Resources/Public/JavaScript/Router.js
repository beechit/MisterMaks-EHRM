(function() {
	'use strict';

	App.Router.map(function() {

			// Administration
		this.resource('administration', function() {
			this.route('applicationSettings', { path: 'settings' });
			this.route('jobDescriptionModule', { path: 'jobdescriptions' });
			this.route('contractArticleModule', { path: 'contractarticles' });
			this.route('userManagementModule', { path: 'usermanagement' });
			this.route('wizardManagementModule', { path: 'wizards' });

				// Contracts
			this.route('contractModule', { path: 'contracts' });
			this.route('contractModule.new', { path: 'contract/new' });
			this.route('contractModule.start', { path: 'contract/start/:contractTemplate/:employee/:jobDescription' });
			this.route('contractModule.show', { path: 'contract/show/:contract'});

				// Beech.Party
			this.resource('BeechPartyPersonAdministrationModule', { path: 'persons' }, function() {
				this.route('new');
				this.resource('BeechPartyDomainModelPersonAdministration', { path: '/:beech_party_domain_model_person_id' }, function() {
					this.route('edit');
				});
			});
			this.resource('BeechPartyCompanyAdministrationModule', { path: 'companies' }, function() {
				this.route('new');
				this.resource('BeechPartyDomainModelCompanyAdministration', { path: '/:beech_party_domain_model_company_id' }, function() {
					this.route('edit');
				});
			});
		});

		this.resource('index', { path: '/' }, function() {
			this.route('dashboard', { path: 'dashboard' });
			this.route('userSettings', { path: 'user/settings' });
			this.route('documentModule', { path: 'documents' });

			// Beech.Party
			this.resource('BeechPartyPersonModule', { path: 'persons' });
			this.resource('BeechPartyDomainModelPerson', { path: 'persons/:beech_party_domain_model_person_id' }, function() {
				this.route('edit');
			});

			this.resource('BeechPartyCompanyModule', { path: 'companies' }, function() {
				this.resource('BeechPartyDomainModelCompany', { path: '/:beech_party_domain_model_company_id' }, function() {
					this.route('edit');
				});
			});

				// Beech.Task
			this.resource('BeechTaskTaskModule', { path: 'tasks' }, function() {
				this.route('new');
				this.resource('BeechTaskDomainModelTask', { path: '/:beech_task_domain_model_task_id' }, function() {
					this.route('edit');
				});
			});
		});
	});

	App.ModuleRoute = Ember.Route.extend({
		renderTemplate: function() {
			this._super.apply(this, arguments);
			this.render(this.get('routeName').replace('.', '/'));
		}
	});

		// Frontend routes
	App.IndexRoute = Ember.Route.extend({
		setupController: function() {
			this.controllerFor('BeechTaskDomainModelTask').set('content', App.BeechTaskDomainModelPriority.find());
		}
	});

	App.IndexIndexView = Ember.View.extend({
		templateName: 'user_interface_dashboard'
	});

	App.IndexUserSettingsView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.userSettings
	});
	App.IndexDocumentModuleView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.documents
	});

	// Administration routes
	App.AdministrationIndexRoute = Ember.Route.extend({
		redirect: function() {
			this.transitionTo('administration.applicationSettings');
		}
	});

	App.AdministrationApplicationSettingsView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.applicationSettings
	});
	App.AdministrationWizardManagementModuleView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.wizardManagementModule
	});
	App.AdministrationUserManagementModuleView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.userManagementModule
	});
	App.AdministrationJobDescriptionModuleView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.jobDescription
	});
	App.AdministrationContractArticleModuleView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.contractArticle
	});

}).call(this);