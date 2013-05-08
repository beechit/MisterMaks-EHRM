(function() {
	'use strict';

	App.Router.map(function() {

			// Administration
		this.resource('administration', function() {
			this.route('applicationSettings', { path: 'settings' });

			this.route('companyModule', { path: 'companies' });

			this.route('jobDescriptionModule', { path: 'jobdescriptions' });
			this.route('jobDescriptionModule.new', { path: 'jobdescription/new' });
			this.route('contractArticleModule', { path: 'contractarticles' });
			this.route('userManagementModule', { path: 'usermanagement' });
			this.route('wizardManagementModule', { path: 'wizards' });

				// Contracts
			this.route('contractModule', { path: 'contracts' });
			this.route('contractModule.new', { path: 'contract/new' });
			this.route('contractModule.start', { path: 'contract/start/:contractTemplate/:employee/:jobDescription' });
			this.route('contractModule.show', { path: 'contract/show/:contract'});

				// Beech.Party
			this.route('personModule', { path: 'persons' });
			this.route('personModule.new', { path: 'person/new' });
			this.route('personModule.show', { path: 'person/show/:person' });
			this.route('personModule.edit', { path: 'person/edit/:person' });
			this.route('personModule.delete', { path: 'person/delete/:person' });
		});

		this.resource('index', { path: '/' }, function() {
			this.route('dashboard', { path: 'dashboard' });
			this.route('userSettings', { path: 'user/settings' });
			this.route('documentModule', { path: 'documents' });

				// Beech.Party

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
	App.AdministrationJobDescriptionModuleNewView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.jobDescriptionNew
	});
	App.AdministrationContractArticleModuleView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.contractArticle
	});

}).call(this);