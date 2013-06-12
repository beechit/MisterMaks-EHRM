(function() {
	'use strict';

	App.Router.map(function() {

			// Administration
		this.resource('administration', function() {
			this.route('applicationSettings', { path: 'settings' });

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

				// Beech.Party/Person
			this.resource('BeechPartyAdministrationPerson', { path: 'persons' }, function() {
				this.route('index', { path: '/list' });
			});
			this.resource('BeechPartyAdministrationPerson', { path: 'person' }, function() {
				this.route('new', { path: '/new' });
				this.route('show', { path: '/show/:person' });
				this.route('edit', { path: '/edit/:person' });
				this.route('delete', { path: '/delete/:person' });
			});
				// Beech.Party/Company
			this.resource('BeechPartyAdministrationCompany', { path: 'companies' }, function() {
				this.route('index', { path: '/list' });
			});
			this.resource('BeechPartyAdministrationCompany', { path: 'company' }, function() {
				this.route('new', { path: '/new' });
				this.route('show', { path: '/show/:company' });
				this.route('edit', { path: '/edit/:company' });
				this.route('delete', { path: '/delete/:company' });
			});
		});

		this.resource('index', { path: '/' }, function() {
			this.route('dashboard', { path: 'dashboard' });
			this.route('userSettings', { path: 'user/settings' });

			// Beech.Document/Document
			//this.route('documentModule', { path: 'documents' });
			this.resource('BeechDocumentDocument', { path: 'documents' }, function() {
				this.route('index', { path: '/list' });
			});
			this.resource('BeechDocumentDocument', { path: 'document' }, function() {
				this.route('new', { path: '/new' });
				this.route('show', { path: '/show/:document' });
				this.route('edit', { path: '/edit/:document' });
				this.route('delete', { path: '/delete/:document' });
			});
			// Beech.Party/Person
			this.resource('BeechPartyPerson', { path: 'persons' }, function() {
				this.route('index', { path: '/list' });
			});
			this.resource('BeechPartyPerson', { path: 'person' }, function() {
				this.route('new', { path: '/new' });
				this.route('show', { path: '/show/:person' });
				this.route('edit', { path: '/edit/:person' });
				this.route('delete', { path: '/delete/:person' });
			});
			// Beech.Party/Company
			this.resource('BeechPartyCompany', { path: 'companies' }, function() {
				this.route('index', { path: '/list' });
			});
			this.resource('BeechPartyCompany', { path: 'company' }, function() {
				this.route('new', { path: '/new' });
				this.route('show', { path: '/show/:company' });
				this.route('edit', { path: '/edit/:company' });
				this.route('delete', { path: '/delete/:company' });
			});
			// Beech.Task
			this.resource('BeechTaskTask', { path: 'tasks' }, function() {
				this.route('new', {path: '/new'});
				this.route('show', {path: '/show/:task'});
				this.route('edit', {path: '/edit/:task'});
				this.route('close', {path: '/close/:task'});
			});
		});
	});

	App.ModuleRoute = Ember.Route.extend({
		renderTemplate: function() {
			this._super.apply(this, arguments);
			this.render(this.get('routeName').replace('.', '/'));
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
	App.AdministrationDomainContractArticleModuleView = Ember.View.extend(App.AjaxModuleViewMixin, {
		url: MM.url.module.contractArticle
	});

}).call(this);