require(
	{
		baseUrl: '//' + window.location.host + '/_Resources/Static/Packages/',
		urlArgs: 'bust=' + (new Date()).getTime(),
		paths: {
			'jquery': 'Beech.Ehrm/Library/jquery',
			'jquery-ui': 'Beech.Ehrm/Library/jquery-ui',
			'jquery-lib': 'Beech.Ehrm/Library/jquery-ui/js/jquery-1.7.2.min',
			'jquery-ui-lib': 'Beech.Ehrm/Library/jquery-ui/js/jquery-ui-1.9.rc1',
			'form': 'Beech.Ehrm/Library/jquery.form',
			'handlebars': 'Radmiraal.Emberjs/emberjs/1.0.0-pre.2/handlebars.min',
			'emberjs': 'Radmiraal.Emberjs/emberjs/1.0.0-pre.2/ember.min',
			'bootstrap': 'Beech.Ehrm/Library/bootstrap/js/bootstrap.min',
			'data-tables-twitterbootstrap': 'Beech.Ehrm/Library/dataTables/media/js/jquery.dataTables.TwitterBootstrap',
			'i18n': 'Beech.Ehrm/Library/requirejs/i18n',
			'ModuleHandler': 'Beech.Ehrm/JavaScript/ModuleHandler'
		},
		shim: {
			'jquery-lib': {
				'exports': 'jQuery'
			},
			'jquery-ui': ['jquery'],
			'bootstrap': ['jquery'],
			'form': ['jquery'],
			'emberjs': {
				'deps': ['jquery', 'handlebars'],
				'exports': 'Ember'
			},
			'data-tables-twitterbootstrap': {
				'deps': [
					'bootstrap',
					'Beech.Ehrm/Library/dataTables/media/js/jquery.dataTables'
				]
			},
			'rGraph': {
				'exports': 'RGraph'
			}
		},
		config: {
			i18n: {
				locale: MM.locale
			}
		}
	},
	[
		'jquery',
		'emberjs',
		'jquery-ui',
		'bootstrap',
		'form',
		'ModuleHandler'
	],
	function ($, Ember) {
		$(document).ready(function () {

			window.App = Ember.Application.create({
				autoinit: false
			});

			if (MM.authenticated) {
				if (MM.init.onLoad) {
					for (var i in MM.init.onLoad) {
						if (i.match(/^[0-9]*$/)) {
							MM.init.onLoad[i].call();
						}
					}
				}

				require(
					[
						'Beech.Ehrm/JavaScript/Application',
						'Beech.Ehrm/JavaScript/Router',
						'Beech.Ehrm/JavaScript/Component/MainMenu',
						'Beech.Ehrm/JavaScript/Component/Dashboard/DashboardWidget',
						'Beech.Ehrm/JavaScript/Component/Dashboard/Widget/AllTodos',
						'Beech.Ehrm/JavaScript/Component/Module/Task',
						'Beech.Ehrm/JavaScript/Component/Module/Settings',
						'Beech.Ehrm/JavaScript/Component/Module/Administration'
					],
					function () {
						/**
						 * If needed window.App = window.App.reopen() can be used here to extend
						 * the Application object
						 */

						if (MM.init.preInitialize) {
							for (var i in MM.init.preInitialize) {
								if (i.match(/^[0-9]*$/)) {
									MM.init.preInitialize[i].call();
								}
							}
					 	}

							// Do onready stuff here...
						window.App.initialize();

						if (MM.init.afterInitialize) {
							for (var i in MM.init.afterInitialize) {
								if (i.match(/^[0-9]*$/)) {
									MM.init.afterInitialize[i].call();
								}
							}
						}

					}
				);
			}
		});
	}
);