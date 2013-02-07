var App;

(function() {
	'use strict';

	App = Ember.Application.create({
		LOG_TRANSITIONS: true,
		LOG_STACKTRACE_ON_DEPRECATION: true,
		Service: {},
		Socket: null,
		SocketMessageListeners: [],

		initializeWebSocket: function() {
			this.Socket = $.gracefulWebSocket('ws://127.0.0.1:8000/', { autoReconnect: true });
			this.Socket.onopen = function(msg) {
				this.send($.cookie('TYPO3_Flow_Session'));
				console.log('Connection successfully opened (readyState ' + this.readyState + ')');
			};
			this.Socket.onclose = function(msg) {
				if(this.readyState == 2) {
					console.log(
						'Closing... The connection is going through the closing handshake (readyState ' + this.readyState + ')'
					);
				} else if(this.readyState == 3) {
					App.Service.Notification.showError('Connection to the server has been lost or could not be opened.');
				} else {
					console.log('Connection closed... (unhandled readyState ' + this.readyState + ')');
				}
			};
			this.Socket.onerror = function(event) {
				console.log(event.data);
			};
			this.Socket.onmessage = function(event) {
				App.SocketMessageListeners.forEach(function(item) {
					item.call(this, event);
				});
			};
		},

		ready: function() {
			this.SocketMessageListeners = Ember.ArrayProxy.create({
				content: []
			});

			this.initializeWebSocket();

			if (MM.init.afterInitialize) {
				for (var i in MM.init.afterInitialize) {
					if (i.match(/^[0-9]*$/)) {
						MM.init.afterInitialize[i].call();
					}
				}
			}

		}
	});

	App.deferReadiness();

	$(document).ready(function () {
		if (MM.init.onLoad) {
			for (var i in MM.init.onLoad) {
				if (i.match(/^[0-9]*$/)) {
					MM.init.onLoad[i].call();
				}
			}
		}

		App.advanceReadiness();
	});
}).call(this);