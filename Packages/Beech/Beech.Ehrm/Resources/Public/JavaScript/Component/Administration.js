define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.AdministrationController = Ember.Controller.extend({
				content: '',
				menu: '',

				loadMenu: function() {
					this.set('menu', '<ul class="nav nav-list"><li class="nav-header">List header</li><li class="active"><a href="fixtures/html/static1.json">Users</a></li>');
				}
			});
			App.AdministrationView = Ember.View.extend({
				templateName: 'administration',
				didInsertElement: function() {
					this.get('controller').loadMenu();
				}
			});
		});
	}
);