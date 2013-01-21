App = Ember.Application.create({
	autoinit: false,
	Service: {},
	Socket: null,
	SocketMessageListeners: null,

	initializeWebSocket: function() {
		this.Socket = $.gracefulWebSocket("ws://127.0.0.1:8000/");
		this.Socket.onopen = function(msg) {
			console.log('Connection successfully opened (readyState ' + this.readyState+')');
		};
		this.Socket.onclose = function(msg) {
			if(this.readyState == 2) {
				console.log(
					'Closing... The connection is going through '
						+ 'the closing handshake (readyState '+this.readyState+')'
				);
			} else if(this.readyState == 3) {
				App.Service.Notification.showError('Connection to the server has been lost or could not be opened.');
				setTimeout(function() {
					App.initializeWebSocket();
				}, 6000);
			} else {
				console.log('Connection closed... (unhandled readyState '+this.readyState+')');
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

	initialize: function() {
		this.SocketMessageListeners = Ember.ArrayProxy.create({
			content: []
		});

		this.initializeWebSocket();

		this._super();
	}
});

$(document).ready(function () {
	if (MM.authenticated) {
		if (MM.init.onLoad) {
			for (var i in MM.init.onLoad) {
				if (i.match(/^[0-9]*$/)) {
					MM.init.onLoad[i].call();
				}
			}
		}

		// TODO: Clean this up if we are not gonna use this anymore

		if (MM.init.preInitialize) {
			for (var i in MM.init.preInitialize) {
				if (i.match(/^[0-9]*$/)) {
					MM.init.preInitialize[i].call();
				}
			}
		}

		App.initialize();

		if (MM.init.afterInitialize) {
			for (var i in MM.init.afterInitialize) {
				if (i.match(/^[0-9]*$/)) {
					MM.init.afterInitialize[i].call();
				}
			}
		}
	}
});