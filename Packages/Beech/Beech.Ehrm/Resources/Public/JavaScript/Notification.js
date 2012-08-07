define(['jquery', 'emberjs', 'Beech.Ehrm/Library/jquery.midgardNotifications'], function(jQuery, Ember) {

	return Ember.Object.create({
		_placeholder: null,
		_timeout: 5000, //1 second = 1000

		initialize: function() {
			var that = this;
			this.set('_placeholder', jQuery('body').midgardNotifications());
		},

		setTimeout: function(timeout) {
			this._timeout = timeout;
		},

		showInfo: function(bodyMessage, timeout) {
			this._show('Info', bodyMessage, 'alert alert-info', [], timeout);
		},

		showSuccess: function(bodyMessage, timeout) {
			this._show('Success', bodyMessage, 'alert alert-success', [], timeout);
		},

		showError: function(bodyMessage, timeout) {
			this._show('Error', bodyMessage, 'alert alert-danger', [], timeout);
		},

		showDialog: function(bodyMessage, actions, timeout) {
			this._show('Dialog', bodyMessage, 'alert', actions, timeout);
		},

		_show: function(title, bodyMessage, className, actions, timeout) {
			if (actions == undefined) {
				actions = [];
			}
			if (timeout == undefined) {
				timeout = this.get('_timeout');
			}
			jQuery(this.get('_placeholder')).data('midgardNotifications').create({
				body: bodyMessage,
				actions: actions,
				timeout: timeout,
				callbacks: {
					beforeShow: function(notify) {
						var notifyElement = notify.getElement();
						if (notifyElement.find('.close').length == 0) { //fix for strange bug with double execution of this callback
							notifyElement.prepend('<button class="close" data-dismiss="alert">×</button><strong>'+title+'</strong>');
						}
						notifyElement
							.addClass(className)
							.find('button').addClass('btn');
					}
				}
			});
		}
	});
});

