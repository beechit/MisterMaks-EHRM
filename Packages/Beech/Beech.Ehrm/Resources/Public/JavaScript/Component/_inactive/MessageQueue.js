define(['jquery', 'emberjs', 'Beech.Ehrm/JavaScript/Notification'], function(jQuery, Ember, Notification) {

	return Ember.ArrayController.create({
		content: [],

		/**
		 * Initialization
		 */
		initialize: function() {
			this.set('content', []);
		},

		/**
		 * Get number of notification in queue
		 */
		count: function() {
			return this.get('content').length;
		},

		/**
		 * Add notification message to queue
		 */
		add: function(messageId, messageText, messageType) {
			this.get('content').pushObject(Ember.Object.create({id: messageId, text: messageText, type: messageType}));
		},

		/**
		 * Get message data by specified id
		 */
		getMessage: function(id) {
			return this.findProperty('id', id);
		},

		/**
		 * Display notification by id.
		 * If id is not specified, it takes first notification from queue
		 * if optional param removeAfter is set to TRUE, remove notification from message queue
		 * @param id
		 * @param removeAfter boolean
		 */
		showMessage: function(id, removeAfter) {

			var messageObject =  (id === undefined) ? this.get('content').objectAt(0) : this.getMessage(id);
			removeAfter = (removeAfter === undefined) ? false : true;

			if (messageObject !== undefined) {
				switch (messageObject.type) {
					case Notification.INFO:
						Notification.showInfo(messageObject.text, 2500);
						break;
					case Notification.LOW:
						Notification.showSuccess(messageObject.text, 5000);
						break;
					case Notification.MODERATE:
						Notification.showDialog(messageObject.text, 0);
						break;
					case Notification.HIGH:
						Notification.showError(messageObject.text, 0);
						break;
					case Notification.WARNING:
						Notification.showError(messageObject.text, 0, false);
						break;
				}
				if (removeAfter) {
					this.removeMessage(id);
				}
			}
		},

		/**
		 * Remove message from message queue.
		 * @param id
		 */
		removeMessage: function(id) {
			var messageObject =  (id === undefined) ? this.get('content').objectAt(0) : this.getMessage(id);
			this.removeObject(messageObject);
		}

	});
});

