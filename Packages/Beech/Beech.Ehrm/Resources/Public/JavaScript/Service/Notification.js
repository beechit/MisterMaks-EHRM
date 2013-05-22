(function() {
	'use strict';

	MM.init.afterInitialize.push(function() {
		App.SocketMessageListeners.pushObject(function(event) {
			var data = $.parseJSON(event.data);
			if (data.notifications) {
				data.notifications.forEach(function(notification) {
					App.Service.Notification.show(notification.message ? notification.label : notification.message, notification.message ? notification.message : notification.label, notification.type, notification.sticky ? 0 : undefined, notification.closeable ? true : false);
				});
			}
			if (data.signals) {
				data.signals.forEach(function(signal) {
					var type, id;
					if(typeof signal == "string") {
						var tmp = signal.split(':');
						type = tmp[0];
						id = tmp.length > 1 ? tmp[1] : false;
					} else {
						type = (signal.type !== "undefined" ? signal.type : "");
						id = (signal.id !== "undefined" ? signal.id : "");
					}
					switch(type) {
						case 'BeechTaskDomainModelTask':
							console.log('task ',id)
							if(id) {
								var _task = App.BeechTaskDomainModelTask.find(id);
								if(_task._data) _task.reload();
							}
							App.BeechTaskDomainModelPriority.find();
							break;
						break;

						default:
							// unknow signal
							console.log('Unknow signal from server', signal);
					}

				});
			}
		});
	});

	App.Service.Notification = Ember.Object.create({
		INFO: "info",
		SUCCESS: "success",
		WARNING: "warning",
		ERROR: "error",

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

		showInfo: function(bodyMessage, title, timeout, removable) {
			this.show(title, bodyMessage, this.INFO, timeout, removable);
		},

		showSuccess: function(bodyMessage, title, timeout, removable) {
			this.show(title, bodyMessage, this.SUCCESS, timeout, removable);
		},

		showWarning: function(bodyMessage, title, timeout, removable) {
			this.show(title, bodyMessage, this.WARNING, timeout, removable);
		},

		showError: function(bodyMessage, title, timeout, removable) {
			this.show(title, bodyMessage, this.ERROR, timeout == undefined ? 0 : timeout, removable == undefined ? false : removable);
		},

		showDialog: function(bodyMessage, title, actions, timeout, priority, removable, callBack) {
			var className;
			if (title === undefined) {
				title = 'Dialog';
			}

			this.show(title, bodyMessage, priority == 'veryHigh' ? 'error' : 'alert', timeout, removable, callBack, actions);
		},

		createListener: function(element, event, action) {
			$(element).on(event, action);
		},

		show: function(title, bodyMessage, type, timeout, removable, callBack, actions) {
			var notification,
				fadeOut = { enabled: false},
				body = bodyMessage;

			if (type == undefined) {
				type = this.INFO;
			}

			if (title) {
				bodyMessage = '<header>' + title + '</header>' + bodyMessage;
			}

			if (timeout === undefined) {
				timeout = this.get('_timeout');
			}
			if (timeout > 0) {
				fadeOut = { enabled: true, delay: timeout };
			}

			if(!fadeOut.enabled) {
				removable = true;
			}

			notification = this.get('_placeholder').notify({
				message: bodyMessage,
				type: type,
				closable: removable,
				transition: 'fade',
				fadeOut: fadeOut,
				onClosed: callBack
			}).show();

			// TODO: reimplement actions
		}
	});

}).call(this);