define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.AdministrationController = App.AdministrationController.reopen({
				content: '',
				menu: '',

				loadMenu: function() {
					this.set('menu', '<ul class="nav nav-list"><li class="nav-header">List header</li><li class="active"><a href="fixtures/html/static1.json">Users</a></li>');
				}
			});
			App.AdministrationView = App.AdministrationView.reopen({
				didInsertElement: function() {
					this.get('controller').loadMenu();
				}
			});
		});
	}
);