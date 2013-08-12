(function() {
	'use strict';

	MM.init.afterInitialize.push(function() {
		App.SocketMessageListeners.pushObject(function(event) {
			var data = $.parseJSON(event.data);

			// notify server notification is clossed
			var reportClosed = function() {
				if (this.notificationId) {
					App.Socket.send('notificationClosed:'+this.notificationId);
				}
			}

			// notifications
			if (data.notifications) {
				data.notifications.forEach(function(notification) {
					App.Service.Notification.show(notification.message ? notification.label : notification.message, notification.message ? notification.message : notification.label, notification.type, notification.sticky ? 0 : undefined, notification.closeable ? true : false, reportClosed, undefined, notification.id);
				});
			}

			//signals
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
						// special types
						//case '..':
						//	break;
						default:
							console.log(type,id)
							// check if type is known as object
							if(App[type] && id) {
								var _object = App[type].find(id);
								if(_object._data) _object.reload();
							} else {
								// unknow signal
								console.log('Unknow signal from server', signal);
							}
					}
				});
			}
		});
	});

}).call(this);