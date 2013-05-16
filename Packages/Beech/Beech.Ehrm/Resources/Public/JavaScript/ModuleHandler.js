(function() {
	'use strict';

	App.ModuleHandler = Ember.Object.create({
		loadingFlag: false,
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
			// only load url when set
			if(url) {
				$.ajax({
					format: 'html',
					dataType: 'html',
					context: this,
					beforeSend: function(){
						this.loadContent('<p><i class="icon-spin icon-spinner icon-3x muted"></i></p>', target);
						this.startAjaxRequest();
					},
					complete: this.finishedAjaxRequest,
					url: url,
					success: function(result) {
						this.loadContent(result, target);
					}
				});
			}
		},

		loadContent: function(html, target) {

			if(html.substr(0,9) == 'redirect:') {
				document.location = '/'+html.substr(9);
				return;
			}

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
				beforeSend: that.startAjaxRequest,
				complete: that.finishedAjaxRequest,
				success: function(result) {
					that.loadContent(result, target);
				}
			});
				// When content of module is loaded, apply datepicker plugin if its necessary
			$moduleContainer.find('.datepicker').on('click', function() {
				$(this).datepicker({showOn:'focus', format: 'dd-mm-yyyy' })
					.on('changeDate', function() {
						$(this).datepicker('hide');
					})
					.focus();
			});
		},

		prepareUrl: function (object) {
			var urlParams = "";
			if (object != '') {
				var first = true;
				for (var key in object) {

					urlParams += (first ? '?' : '&') + key + "=" + object[key];
				}
			}
			return urlParams;
		},

		startAjaxRequest: function() {
			$('body').addClass('ajax-loading');
		},

		finishedAjaxRequest: function() {
			$('body').removeClass('ajax-loading');
		}

	});

}).call(this);
