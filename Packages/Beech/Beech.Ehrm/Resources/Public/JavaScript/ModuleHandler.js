(function() {
	'use strict';

	App.ModuleHandlerAjaxController = Ember.Controller.extend({
		url: '',
		loadUrl: function(params) {
			App.ModuleHandler.loadUrl(this.get('url')+params);
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

		loadUrl: function(url, target) {
			// only load url when set
			console.log(url)
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

			if (replaceWith) {
				var $moduleParent = $($moduleContainer.parent());
				$moduleContainer.replaceWith(html);
				$moduleContainer = $moduleParent;
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
								$(target).parents('.modal-body').scrollTop(0);
								that.loadContent(result, target);
							}
						} else {
							$(target).parents('.modal-body').scrollTop(0);
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
			$moduleContainer.find('select').chosen();
			$moduleContainer.find('input').applyInputMasks();
			$moduleContainer.find('.countrySelect').countrySelect();
			$moduleContainer.find('input[type=checkbox].primary').controlPrimary();
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
