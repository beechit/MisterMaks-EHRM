define(['jquery', 'jquery-ui-lib'], function($) {
	window.JQ = {};

	/**
	 * Create a new mixin for jQuery UI widgets using the Ember
	 * mixin syntax.
	 */
	window.JQ.Widget = Em.Mixin.create({

		uiWidget: function(options) {
			var ui;

				// Create a new instance of the jQuery UI widget based on its `uiType` and the current element.
			if (this.get('uiType').substr(0, 7) == 'Midgard') {
				ui = jQuery.Midgard[this.get('uiType').substr(8)];
			} else {
				ui = jQuery.ui[this.get('uiType')];
			}

			return ui;
		}.property().cacheable(),

		didInsertElement: function() {
			this._super();
			var options = this._gatherOptions();
			this._gatherEvents(options);

			var ui = this.get('uiWidget')(options, this.get('element'));
			this.set('ui', ui);
		},

		willDestroyElement: function() {
			var ui = this.get('ui');
			if (ui) {
				var observers = this._observers;
				for (var prop in observers) {
					if (observers.hasOwnProperty(prop)) {
						this.removeObserver(prop, observers[prop]);
					}
				}
				ui._destroy();
			}
		},

		didCreateWidget: Ember.K,

		concatenatedProperties: ['uiEvents', 'uiOptions', 'uiMethods'],

		uiEvents: ['create'],
		uiOptions: ['disabled'],
		uiMethods: [],

		_gatherEvents: function(options) {
			var uiEvents = this.get('uiEvents');

			uiEvents.forEach(function(eventType) {
				var eventHandler = eventType === 'create' ? this.didCreateWidget : this[eventType];
				if (eventHandler) {
					options[eventType] = $.proxy(function(event, ui) {
						eventHandler.call(this, event, ui);
					}, this);
				}
			}, this);
		},

		_gatherOptions: function() {
			var uiOptions = this.get('uiOptions'),
				options = {},
				that = this,
				defaultOptions = {};

			if (this.get('uiWidget').prototype) {
				defaultOptions = this.get('uiWidget').prototype.options;
			} else if (this.get('uiWidget').options) {
				defaultOptions = this.get('uiWidget').options;
			}

			uiOptions.forEach(function(key) {
				var ui,
					value = that.get(key),
					uiKey = key.replace(/^_/, '');

				if (value) {
					options[uiKey] = value;
				} else {
					this.set(key, defaultOptions[uiKey]);
				}

					// TODO: Add observer for the actual widget options, not just the ui options
				var observer = function() {
					var value = that.get(key);
					ui = that.get('ui');
					if (ui.options[uiKey] != value) {
						ui._setOption(uiKey, value);
					}
				};

				this.addObserver(key, observer);
			}, this);

			return options;
		}
	});
});