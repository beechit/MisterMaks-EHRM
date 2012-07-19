define(['jquery', 'emberjs', 'Beech.Ehrm/Library/jquery.midgardNotifications'], function(jQuery, Ember) {

	return Ember.Object.create({
		_placeholder: null,

		initialize: function() {
			var that = this;

			this.set('_placeholder', jQuery('body').midgardNotifications());

			var actions =
				[
					{
						name: 'yes', label: 'Yes',
						cb: function(e, notification) {
							Notification.showSuccess('Success message');
							notification.close();
						}
					},
					{
						name: 'no', label: 'No',
						cb: function(e, notification) {
							notification.close();
						}
					}
				];

			jQuery('.notify-info').live('click', function() {that.showInfo('Info message');});
			jQuery('.notify-success').live('click', function() {that.showSuccess('Success message');});
			jQuery('.notify-error').live('click', function() {that.showError('Error message');});
			jQuery('.notify-dialog').live('click', function() {that.showDialog('Question?', actions);});
		},

		showInfo: function(bodyMessage) {
			this._show('Info', bodyMessage, 'alert alert-info');
		},

		showSuccess: function(bodyMessage) {
			this._show('Success', bodyMessage, 'alert alert-success');
		},

		showError: function(bodyMessage) {
			this._show('Error', bodyMessage, 'alert alert-danger');
		},

		showDialog: function(bodyMessage, actions) {
			this._show('Dialog', bodyMessage, 'alert', actions);
		},

		_show: function(title, bodyMessage, className, actions) {
			if (actions == undefined) {
				actions = [];
			}

			jQuery(this.get('_placeholder')).data('midgardNotifications').create({
				body: bodyMessage,
				actions: actions,
				timeout: 0,
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

