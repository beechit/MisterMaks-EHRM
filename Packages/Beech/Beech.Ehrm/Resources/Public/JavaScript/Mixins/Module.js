(function() {
	'use strict';

	App.ModuleControllerMixin = Ember.Mixin.create({
		content: '',
		contentUrl: null,
		menu: '',
		menuUrl: null,
		loadImage: '<p><i class="icon-spin icon-spinner icon-4x muted"></i></p>',
		loadContent: function() {
			if (this.get('contentUrl') !== null) {
				this.set('content', this.get('loadImage'));
				App.ModuleHandler.loadUrl(this.get('contentUrl'));
			}
		},
		loadMenu: function() {
			if (this.get('menuUrl') !== null) {
				this.set('menu', this.get('loadImage'));
				App.ModuleHandler.loadUrl(this.get('menuUrl'), '.ehrm-module-menu');
			}
		}
	});

	App.ModuleViewMixin = Ember.Mixin.create({
		didInsertElement: function() {
			this.get('controller').loadContent();
			this.get('controller').loadMenu();
		}
	});

}).call(this);