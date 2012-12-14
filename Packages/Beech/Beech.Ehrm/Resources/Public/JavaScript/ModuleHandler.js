(function() {
	'use strict';

	App.ModuleHandler = Ember.Object.create({
		init: function() {
			var that = this;

			$('.ehrm-module a, .ehrm-module-menu a').live('click', function(event) {
				if ($(this).attr('href').match(/#/) === null) {
					that.loadUrl($(this).attr('href'), '.ehrm-module');
					return false;
				}
			});
		},

		loadUrl: function(url, target) {
			$.ajax({
				format: 'jsonp',
				dataType: 'jsonp',
				context: this,
				url: this.jsonpifyUrl(url),
				success: function(result) {
					this.loadContent(result.html, target);
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
				$(this).attr('action', that.jsonpifyUrl($(this).attr('action')));
			});

			$moduleContainer.find('form').ajaxForm({
				dataType: 'jsonp',
				success: function(result) {
					that.loadContent(result.html, '.ehrm-module');
				}
			});
		},

		jsonpifyUrl: function(url) {
			url = url.replace('.html', '.jsonp');
			if (!url.match(/\.jsonp/)) {
				url = url.replace('?', '.jsonp?');
			}
			return url;
		}
	});

}).call(this);