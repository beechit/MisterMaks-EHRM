(function() {
	'use strict';

	App.AjaxModuleControllerMixin = Ember.Mixin.create({

		url: '',

		renderTemplate: function() {
			var that = this;

			this._super();
			this.render('jsonp_module');

			if (this.get('url') == '') {
				return;
			}

			setTimeout(function() {
				$.ajax({
					format: 'jsonp',
					dataType: 'jsonp',
					context: this,
					url: that.get('url'),
					success: function(result) {
						$('.ehrm-module').html(result.html);
					}
				});
			}, 10);
		}
	});

}).call(this);