define(
	[
		'jquery',
		'emberjs'
	],
	function ($, Ember) {
		MM.init.preInitialize.push(function() {
			App.Router = Ember.Router.extend({
				enableLogging: true,
				root: Ember.Route.extend({
					// Transitions
					showModuleDocument: Ember.Route.transitionTo('moduleDocument'),
					showModuleTask: Ember.Route.transitionTo('moduleTask'),
					showSettings: Ember.Route.transitionTo('settings'),
					showManagement: Ember.Route.transitionTo('management'),
					showAdministration: Ember.Route.transitionTo('administration'),
					showDashboard: Ember.Route.transitionTo('dashboard'),

					// Routes
					dashboard: Ember.Route.extend({
						route: '/',
						connectOutlets: function(router){
							router.get('applicationController').connectOutlet('dashboard', App.Todo.find());
						}
					}),
					moduleDocument: Ember.Route.extend({
						route: '/documents',
						connectOutlets: function(router) {
							router.get('applicationController').connectOutlet('moduleDocument');
						}
					}),
					moduleTask: Ember.Route.extend({
						route: '/tasks',
						connectOutlets: function(router) {
							router.get('applicationController').connectOutlet('moduleTask');
						}
					}),
					management: Ember.Route.extend({
						route: '/management',
						connectOutlets: function(router) {
							router.get('applicationController').connectOutlet('management');
						}
					}),
					administration: Ember.Route.extend({
						route: '/administration',
						connectOutlets: function(router) {
							router.get('applicationController').connectOutlet('administration');
						}
					}),
					settings: Ember.Route.extend({
						route: '/settings',
						connectOutlets: function(router) {
							router.get('applicationController').connectOutlet('settings');
						}
					})
				})
			});
		});
	}
);