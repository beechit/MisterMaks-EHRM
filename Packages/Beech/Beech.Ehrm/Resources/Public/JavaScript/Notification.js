define(['jquery', 'emberjs', 'Beech.Ehrm/Library/jquery.midgardNotifications'], function(jQuery, Ember) {

	return Ember.Object.create({
		_placeholder: null,

		initialize: function() {
			var that = this;
			this.set('_placeholder', jQuery('body').midgardNotifications());
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

		showDialog: function(bodyMessage, actions, title, priority) {
			var className;
			if (title == undefined) {
				title = 'Dialog';
			}
			if(priority == 'veryHigh')
				className = 'alert alert-error';
			else
				className = 'alert';

			this._show(title, bodyMessage, className, actions);
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

