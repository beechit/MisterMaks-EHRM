require(
	{
		baseUrl: '//' + window.location.host + '/_Resources/Static/Packages/',
		urlArgs: 'bust=' + (new Date()).getTime(),
		paths: {
			'jquery': 'Beech.Ehrm/JavaScript/jquery',
			'jquery-lib': 'Beech.Ehrm/Library/jquery-ui/js/jquery-1.7.2.min',
			'jquery.ui': 'Beech.Ehrm/Library/jquery-ui/js/jquery-ui-1.8.21.custom.min',
			'emberjs': 'Emberjs/Core/minified/ember-0.9.8.min',
			'bootstrap': 'Twitter.Bootstrap/2.0/js/bootstrap.min',
			'notification': 'Beech.Ehrm/JavaScript/Notification',
			'message-queue': 'Beech.Ehrm/JavaScript/MessageQueue',
			'ui': 'Beech.Ehrm/JavaScript/UserInterface'
		},
		shim: {
			'jquery-lib': {
				'exports': 'jQuery'
			},
			'jquery.ui': ['jquery'],
			'bootstrap': ['jquery'],
			'emberjs': {
				'deps': ['jquery'],
				'exports': 'Ember'
			}
		}
	},
	[
		'jquery',
		'emberjs',
		'jquery.ui',
		'bootstrap'
	],
	function(jQuery, Ember) {
		jQuery(document).ready(function() {
			if (MM.authenticated) {
				require(['ui', 'notification', 'message-queue'], function(UserInterface, Notification, MessageQueue) {
					window.App = Ember.Application.create({
						ready: function() {
							UserInterface.modal().initialize();
							Notification.initialize();
							MessageQueue.initialize();

							jQuery.get('rest/notification/', function(data) {
								data = jQuery.parseJSON(data);

								if (data.result.status === 'success') {
									jQuery.each(data.objects, function(index, value) {
										Notification.showDialog(
											value.urls.execute ? '<a href="' + value.urls.execute + '">Do task</a>' : '', // TODO: Add localization for 'Do Task'
											[],
											value.sticky ? 0 : 500, // TODO: Check why the timeout doesn't work
											'Notification: ' + value.label,
											'normal',
											value.closeable,
											function() {
												console.log('after show callback');
												jQuery.ajax(
													'rest/notification/' + value.identifier,
													{
														'type': 'DELETE'
													}
												);
											}
										);
											// TODO: Add closeable / no
									});
								} else {
									Notification.showError('Communication error');
								}
							});
						}
					});
				});
			}
		});
	}
);