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
			'handlebars': 'Beech.Ehrm/Library/emberjs/handlebars-1.0.rc.1',
			'emberjs': 'Beech.Ehrm/Library/emberjs/ember-1.0.0-pre.2',
			'bootstrap': 'Beech.Ehrm/Library/bootstrap/js/bootstrap.min',
			'data-tables-twitterbootstrap': 'Beech.Ehrm/Library/dataTables/media/js/jquery.dataTables.TwitterBootstrap',
			'i18n': 'Beech.Ehrm/Library/requirejs/i18n'
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
		'form'
	],
	function ($, Ember) {
		$(document).ready(function () {

			var setModuleHtml = function(html) {
				$('.ehrm-module').html(html).find('form').each(function() {
					$(this).attr('action', jsonpifyUrl($(this).attr('action')));
				});

				$('.ehrm-module').find('form').ajaxForm({
					dataType: 'jsonp',
					success: function(result) {
						setModuleHtml(result.html);
					}
				});


			}, jsonpifyUrl = function(url) {
				url = url.replace('.html', '.jsonp');
				if (!url.match(/\.jsonp/)) {
					url = url.replace('?', '.jsonp?');
				}
				return url;
			};

			$('.ehrm-module a').live('click', function(event) {
				if ($(this).attr('href').match(/#/) === null) {
					$.ajax({
						format: 'jsonp',
						dataType: 'jsonp',
						context: this,
						url: jsonpifyUrl($(this).attr('href')),
						success: function(result) {
							setModuleHtml(result.html);
						}
					});
					return false;
				}
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
						'Beech.Ehrm/JavaScript/Component/Application',
						'Beech.Ehrm/JavaScript/Component/MainMenu',
						'Beech.Ehrm/JavaScript/Component/Dashboard',
						'Beech.Ehrm/JavaScript/Component/Module/Task',
						'Beech.Ehrm/JavaScript/Component/Module/Document',
						'Beech.Ehrm/JavaScript/Component/DashboardWidget',
						'Beech.Ehrm/JavaScript/Component/Todo',
						'Beech.Ehrm/JavaScript/Component/AllTodos',
						'Beech.Ehrm/JavaScript/Component/Settings',
						'Beech.Ehrm/JavaScript/Component/Management',
						'Beech.Ehrm/JavaScript/Component/Administration',
						'Beech.Ehrm/JavaScript/Component/Router'
					],
					function () {
						window.App = Ember.Application.create({
							autoinit: false
						});

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