define(['jquery', 'emberjs', 'Beech.Ehrm/Library/jquery.midgardNotifications'], function(jQuery, Ember) {

	MM.init.afterInitialize.push(function() {
		jQuery.get(MM.configuration.restNotificationUri, function(data) {
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
	});

	return Ember.Object.create({
		INFO: "Info",
		LOW: "Low action",
		MODERATE: "Moderate action",
		HIGH: "High action",
		WARNING: "Warning",

		_placeholder: null,
		_timeout: 5000, //1 second = 1000

		initialize: function() {
			this.set('_placeholder', jQuery('body').midgardNotifications());
		},

		setTimeout: function(timeout) {
			this.set('_timeout', timeout);
		},

		showInfo: function(bodyMessage, timeout, callBack) {
			this._show('Info', bodyMessage, 'alert alert-info', [], timeout);
		},

		showSuccess: function(bodyMessage, timeout, callBack) {
			this._show('Success', bodyMessage, 'alert alert-success', [], timeout);
		},

		showError: function(bodyMessage, timeout, removable, callBack) {
			this._show('Error', bodyMessage, 'alert alert-danger', [], timeout, removable);
		},

		showDialog: function(bodyMessage, actions, timeout, title, priority, removable, callBack) {
			var className;
			if (title === undefined) {
				title = 'Dialog';
			}
			if(priority === 'veryHigh') {
				className = 'alert alert-error';
			} else {
				className = 'alert';
			}

			this._show(title, bodyMessage, className, actions, timeout, removable, callBack);
		},

		createListener: function(element, event, action) {
			jQuery(element).live(event, action);
		},

		_show: function(title, bodyMessage, className, actions, timeout, removable, callBack) {
			if (actions === undefined) {
				actions = [];
			}
			if (timeout === undefined) {
				timeout = this.get('_timeout');
			}
			if (removable === undefined) {
				removable = true;
			}
			jQuery(this.get('_placeholder')).data('midgardNotifications').create({
				body: bodyMessage,
				actions: actions,
				timeout: 0,
				callbacks: {
					beforeShow: function(notify) {
						var notifyElement = notify.getElement();
						if (notifyElement.find('.close').length === 0) {
								// TODO: fix for strange bug with double execution of this callback
							notifyElement.prepend('<button class="close btn">×</button><strong>' + title + '</strong>')
								.addClass(className)
								.find('button').click(function() {
									notify.close();
								}
							);
							if (!removable) {
								notifyElement.find('.close').hide();
							}
						}
					},
					afterShow: function() {
						console.log('after show', callBack);
						if (callBack) {
							callBack.call();
						}
					},
						//to prevent default close on click action
					onClick: function(event) {
						if (jQuery(event.target).hasClass('close')) {
							return true;
						}
						return false;
					}
				}
			});
		}
	});
});

