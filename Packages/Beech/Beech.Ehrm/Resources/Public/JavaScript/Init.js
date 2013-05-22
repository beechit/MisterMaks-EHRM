var App;

(function() {
	'use strict';

	App = Ember.Application.create({
		LOG_TRANSITIONS: true,
		LOG_STACKTRACE_ON_DEPRECATION: true,
		Service: {},
		Socket: null,
		SocketMessageListeners: [],
		SocketConnectAttempts: 0,
		SocketMaxConnectAttempts: 100,
		SocketFirstConnectAttempt: true,
		initializeWebSocket: function() {
			this.SocketConnectAttempts++;
			this.Socket = $.gracefulWebSocket('ws://'+document.domain+':8000/');
			this.Socket.onopen = function() {
				this.send('auth:'+$.cookie('TYPO3_Flow_Session'));
				console.log('Connection successfully opened (readyState ' + this.readyState + ') attempts: '+App.SocketConnectAttempts);
				App.SocketConnectAttempts = 0;
				App.SocketFirstConnectAttempt = false;
			};
			this.Socket.onclose = function() {
				if(this.readyState === 2) {
					console.log(
						'Closing... The connection is going through the closing handshake (readyState ' + this.readyState + ')'
					);
				} else if(this.readyState === 3) {
					if(App.SocketFirstConnectAttempt) {
						// ping websocketserver when we dont get a response at first attempt
						$.get('/Beech.ehrm/application/pingWebSocketServer');
						App.SocketFirstConnectAttempt = false;
					}
					if(App.SocketConnectAttempts == 0) {
						App.Service.Notification.showWarning('Connection to the server has been lost trying to reconnect');
					}
					if(App.SocketConnectAttempts < App.SocketMaxConnectAttempts) {
						setTimeout('App.initializeWebSocket()', 1000);
					} else {
						App.Service.Notification.showError('Connection to the server has been lost or could not be opened');
					}
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