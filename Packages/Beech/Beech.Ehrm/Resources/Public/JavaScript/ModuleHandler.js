(function() {
	'use strict';

	App.ModuleHandler = Ember.Object.create({
		init: function() {
			var that = this;

			$('.ehrm-module a, .ehrm-module-menu a').on('click', function() {
				if ($(this).attr('href').match(/#/) === null && $(this).hasClass('escape-ajax-module') === false) {
					that.loadUrl($(this).attr('href'), '.ehrm-module');
					return false;
				}
			});
		},

		loadUrl: function(url, target) {
			$.ajax({
				format: 'html',
				dataType: 'html',
				context: this,
				url: url,
				success: function(result) {
					this.loadContent(result, target);
				}
			});
		},

		loadContent: function(html, target) {
			if (!target) {
				target = '.ehrm-module';
			}
			var that = this, $moduleContainer = $(target);

			$moduleContainer.html(html);

			$moduleContainer.find('form').each(function() {
				$(this).attr('action', $(this).attr('action'));
			});

			$moduleContainer.find('form').ajaxForm({
				dataType: 'html',
				success: function(result) {
					that.loadContent(result, target);
				}
			});
		},

		prepareUrl: function (object) {
			var urlParams = "";
			if (object != '') {
				for (var key in object) {
					urlParams += "&" +key + "=" + object[key];
				}
			}
			return urlParams;
		}
	});

}).call(this);