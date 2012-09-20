require(
	{
		baseUrl: '//' + window.location.host + '/_Resources/Static/Packages/',
		urlArgs: 'bust=' + (new Date()).getTime(),
		paths: {
			'jquery': 'Beech.Ehrm/JavaScript/jquery',
			'jquery-ui': 'Beech.Ehrm/JavaScript/jquery-ui',
			'jquery-lib': 'Beech.Ehrm/Library/jquery-ui/js/jquery-1.7.2.min',
			'jquery-ui-lib': 'Beech.Ehrm/Library/jquery-ui/js/jquery-ui-1.9.rc1',
			'form': 'Beech.Ehrm/Library/jquery.form',
			'emberjs': 'Emberjs/Core/ember-0.9.8',
			'bootstrap': 'Twitter.Bootstrap/2.0/js/bootstrap.min',
			'data-tables-twitterbootstrap': 'Beech.Ehrm/Library/dataTables/media/js/jquery.dataTables.TwitterBootstrap'
		},
		shim: {
			'jquery-lib': {
				'exports': 'jQuery'
			},
			'jquery-ui': ['jquery'],
			'bootstrap': ['jquery'],
			'form': ['jquery'],
			'emberjs': {
				'deps': ['jquery'],
				'exports': 'Ember'
			},
			'data-tables-twitterbootstrap': {
				'deps':
				[
					'bootstrap',
					'Beech.Ehrm/Library/dataTables/media/js/jquery.dataTables'
				]
			}
		}
	},
	[
		'jquery',
		'emberjs',
		'jquery-ui',
		'bootstrap'
	],
	function($, Ember) {
		$(document).ready(function() {
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
						'Beech.Ehrm/JavaScript/UserInterface',
						'Beech.Ehrm/JavaScript/Notification',
						'Beech.Ehrm/JavaScript/MessageQueue',
						'Beech.Ehrm/JavaScript/Log'
					],
					function(UserInterface, Notification, MessageQueue, Log) {
						window.App = Ember.Application.create({

							View: {
								Log: Log
							},

							ready: function() {
								if (MM.init.preInitialize) {
									for (var i in MM.init.preInitialize) {
										if (i.match(/^[0-9]*$/)) {
											MM.init.preInitialize[i].call();
										}
									}
								}

								UserInterface.modal().initialize();
								Notification.initialize();
								MessageQueue.initialize();

								if (MM.init.afterInitialize) {
									for (var i in MM.init.afterInitialize) {
										if (i.match(/^[0-9]*$/)) {
											MM.init.afterInitialize[i].call();
										}
									}
								}
							}
						});
					}
				);
			}
		});
	}
);