(function() {
	'use strict';

	MM.init.afterInitialize.push(function() {
		App.SocketMessageListeners.pushObject(function(event) {
			var data = $.parseJSON(event.data);
			if (data.notifications) {
				data.notifications.forEach(function(notification) {
					App.Service.Notification.showInfo(notification.message, 5000);
				});
			}
		});
	});

	App.Service.Notification = Ember.Object.create({
		INFO: "Info",
		LOW: "Low action",
		MODERATE: "Moderate action",
		HIGH: "High action",
		WARNING: "Warning",

		_placeholder: null,
		_timeout: 5000, //1 second = 1000

		init: function() {
			var that = this;
			MM.init.onLoad.push(function() {
				that.set('_placeholder', $('<div />', {'class': 'notifications top-right'}).appendTo($('body')));
			});
		},

		setTimeout: function(timeout) {
			this.set('_timeout', timeout);
		},

		showInfo: function(bodyMessage, timeout) {
			this._show('Info', bodyMessage, 'info', [], timeout);
		},

		showSuccess: function(bodyMessage, timeout) {
			this._show('Success', bodyMessage, 'success', [], timeout);
		},

		showError: function(bodyMessage, timeout, removable) {
			this._show('Error', bodyMessage, 'danger', [], timeout, removable);
		},

		showDialog: function(bodyMessage, actions, timeout, title, priority, removable, callBack) {
			var className;
			if (title === undefined) {
				title = 'Dialog';
			}

			this._show(title, bodyMessage, priority == 'veryHigh' ? 'error' : 'alert', actions, timeout, removable, callBack);
		},

		createListener: function(element, event, action) {
			$(element).live(event, action);
		},

		_show: function(title, bodyMessage, type, actions, timeout, removable, callBack) {
			var notification,
				fadeOut = null,
				body = bodyMessage;

			if (title) {
				bodyMessage = '<header>' + title + '</header>' + bodyMessage;
			}

			if (!timeout) {
				timeout = this.get('_timeout');
			}
			if (timeout > 0) {
				fadeOut = { enabled: true, delay: timeout };
			}

			notification = this.get('_placeholder').notify({
				message: bodyMessage,
				type: type,
				closeable: removable,
				transition: 'fade',
				fadeOut: fadeOut,
				onClosed: callBack
			}).show();

			// TODO: reimplement actions
		}
	});

}).call(this);