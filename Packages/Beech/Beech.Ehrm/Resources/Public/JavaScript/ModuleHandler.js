(function() {
	'use strict';

	App.ModuleHandlerAjaxController = Ember.Controller.extend({
		url: '',
		loadUrl: function(params) {
			if (this.get('url').indexOf('?') > 0 && params.substr(0,1) == '?') {
				App.ModuleHandler.loadUrl(this.get('url')+'&'+params.substr(1));
			} else {
				App.ModuleHandler.loadUrl(this.get('url')+params);
			}

		}
	});

	App.ModuleHandlerAjaxRoute = App.ModuleRoute.extend({
		deserialize: function(params){
			return {params: App.ModuleHandler.prepareUrl(params)}
		},
		setupController: function(controller, model) {
			controller.loadUrl(model.params);
		}
	});

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

		loadUrl: function(url, target, callback) {
			// only load url when set
			if(url) {
				var $callback = callback;
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
						if (typeof($callback) == "function") {
							$callback(result, target);
						}
					},
					statusCode: {
						400: function(xhr,status,message) {
							this.loadContent(xhr.responseText, target);
						},
						401: function(xhr,status,message) {
							App.Service.Notification.showWarning(message);
							window.location = $('base').text();
						},
						403: function(xhr,status,message) {
							App.Service.Notification.showError(message);
						},
						404: function(xhr,status,message) {
							App.Service.Notification.showError(message);
						},
						500: function(xhr,status,message) {
							App.Service.Notification.showError(message);
						}
					}
				});
			}
		},

		loadContent: function(html, target, replaceWith) {
			if(html.substr(0,9) == 'redirect:') {
				document.location = '/'+html.substr(9);
				return;
			}

			if (!target) {
				target = '.ehrm-module';
			}
			var that = this, $moduleContainer = $(target);
			var $primary;
			if (replaceWith) {
				var $moduleParent = $($moduleContainer.parent());
				$moduleContainer.replaceWith(html);

				$moduleContainer = $moduleParent;
				$primary = $($.parseHTML( html )).find('input[type=checkbox].primary:checked');
			} else {
				$moduleContainer.html(html);
			}

			$moduleContainer.find('form').each(function() {
				$(this).attr('action', $(this).attr('action'));
			});

			$moduleContainer.find('form').each(function(index, form) {

				$(form).ajaxForm({
					dataType: 'html',
					beforeSend: that.startAjaxRequest,
					complete: that.finishedAjaxRequest,
					success: function(result) {
						if ($(form).parent().parent().attr('id') != '') {
							if ($(form).hasClass('remove')) {
								that.loadContent(result, '#'+$(form).parent().parent().attr('id'));
							} else if ($(form).hasClass('add')) {
								that.loadContent(result, '#'+$(form).parent().parent().attr('id'), true);
							} else if($('#'+$(form).parent().attr('id')).length) {
								that.loadContent(result, '#'+$(form).parent().attr('id'), true);
							} else {
								$(target).scrollTop(0);
								that.loadContent(result, target);
							}
						} else {
							$(target).scrollTop(0);
							that.loadContent(result, target);
						}
					},
					error: function(result) {
						$(form).parent().parent().prepend(result.responseText);
					}
				});
			});
				// When content of module is loaded, apply datepicker plugin if its necessary
			$moduleContainer.find('.datepicker').on('click', function() {
				$(this).datepicker({showOn:'focus', format: 'dd-mm-yyyy' })
					.on('changeDate', function() {
						$(this).datepicker('hide');
					})
					.focus();
			});
			$moduleContainer.find('select:not(".input-mini")').chosen();
			$moduleContainer.find('input').applyInputMasks();
			$moduleContainer.find('.countrySelect').countrySelect();
			if ($primary != undefined) {
				$primary.controlPrimary();
			}

			if ($moduleContainer.find('.modal-header.custom').length > 0 && $moduleContainer.parent().find('.modal-header:not(.custom)').length > 0) {
				var $customHeader = $moduleContainer.find('.modal-header.custom')
				$moduleContainer.parent().find('.modal-header:not(.custom)').replaceWith($customHeader);
				$customHeader.removeClass('custom');
			}
			if ($moduleContainer.find('.modal-footer.custom').length > 0 && $moduleContainer.parent().find('.modal-footer:not(.custom)').length > 0) {
				var $customFooter = $moduleContainer.find('.modal-footer.custom')
				$moduleContainer.parent().find('.modal-footer:not(.custom)').replaceWith($customFooter);
				$customFooter.removeClass('custom');
			}
			$moduleContainer.parent().find('.modal-footer').removeClass('hide');
			$moduleContainer.parent().find('.modal-footer .btn').each(function(i, button) {
				$(button).unbind('click');
				if ($(button).attr('data-reload') != '') {
					$(button).on('click', function(event) {
						that.loadUrl($(button).attr('data-reload'))
					})
				}
			})
		},

		prepareUrl: function (object) {
			var urlParams = "";
			if (object != '') {
				var first = true;
				for (var key in object) {
					urlParams += (first ? '?' : '&') + key + "=" + object[key];
					first = false;
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
