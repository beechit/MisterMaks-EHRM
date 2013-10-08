(function() {
	'use strict';

	App.ModuleHandlerAjaxController = Ember.Controller.extend({
		url: '',
		currentUrl: '',
		loadUrl: function(params) {
			if (!params) {
				this.set('currentUrl', this.get('url'));
			} else if (this.get('url').indexOf('?') > 0 && params.substr(0,1) == '?') {
				this.set('currentUrl', this.get('url')+'&'+params.substr(1));
			} else {
				this.set('currentUrl', this.get('url')+params);
			}
			App.ModuleHandler.loadUrl(this.get('currentUrl'));
		},
		refresh: function() {
			App.ModuleHandler.loadUrl(this.get('currentUrl'));
		}
	});

	App.ModuleHandlerAjaxRoute = App.ModuleRoute.extend({
		setupController: function(controller, model) {
		//	console.log('setupController in route')
			if (model) {
				controller.loadUrl(App.ModuleHandler.prepareUrl(model));
			} else {
				controller.loadUrl()
			}
		},
		// fix overriding routes with params
		// ember expects a model but our setup breaks when there is a same route without params
		serialize: function(model, params) {
			if (!model) model = {}
			return this._super(model, params);
		}
	});

	App.ModuleHandler = Ember.Object.create({
		loadingFlag: false,
		currentRequest: null,
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
				// stop current request
				if (this.currentRequest) {
					this.currentRequest.abort();
				}
				if (url == 'close-modal') {
					return;
				} else if (url == 'reload-current-page') {
					App.refreshCurrentPath();
					return;
				} else if (url.substr(0,9) == 'redirect:') {
					if (document.location.hash == url.substr(9)) {
						App.refreshCurrentPath();
					} else {
						document.location = '/'+url.substr(9);
					}
					return;
				}

				this.currentRequest = $.ajax({
					format: 'html',
					dataType: 'html',
					context: this,
					beforeSend: function(){
						this.showLoading(target);
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
		showLoading: function(target) {
			if (!target) {
				target = '.ehrm-module';
			}
			$(target).html('<p><i class="icon-spin icon-spinner icon-3x muted"></i></p>', target);
		},
		loadContent: function(html, target, replaceWith) {

			if (!html) {
				return;
			} else if (html == 'close-modal') {
				return;
			} else if (html == 'reload-current-page') {
				App.refreshCurrentPath();
				return;
			} else if (html.substr(0,9) == 'redirect:') {
				if (document.location.hash == html.substr(9)) {
					App.refreshCurrentPath();
				} else {
					document.location = '/'+html.substr(9);
				}
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
					beforeSubmit: that.beforeSubmit,
					complete: that.finishedAjaxRequest,
					success: function(result) {
						var replaceWith = false, redirect = false;

						// check if response is redirect
						redirect = (result == 'close-modal' || result == 'reload-current-page' || result.substr(0,9) == 'redirect:');

						$(form).removeAttr('isSubmitting')
						// override target
						if ($(form).parent().parent().attr('id') != '') {
							if ($(form).hasClass('remove')) {
								target = '#'+$(form).parent().parent().attr('id');
							} else if ($(form).hasClass('add')) {
								target = '#'+$(form).parent().parent().attr('id');
								replaceWith = true;
							} else if($('#'+$(form).parent().attr('id')).length) {
								target = '#'+$(form).parent().attr('id');
								replaceWith = true;
							} else {
								$(target).scrollTop(0);
							}
						} else {
							$(target).scrollTop(0);
						}

						// if data-reload is set on btn-save use that location
						if ($moduleContainer.parent().find('.btn-save').is(':visible') && $moduleContainer.parent().find('.btn-save').attr('data-reload') != undefined) {
							that.loadUrl($moduleContainer.parent().find('.btn-save').attr('data-reload'));
							$moduleContainer.parent().modal('hide');
						} else if (redirect && $moduleContainer.parents('.modal').attr('data-reload')) {
							that.loadUrl($moduleContainer.parents('.modal').attr('data-reload'));
							$moduleContainer.parent().modal('hide');
						} else {
							that.loadContent(result, target, replaceWith);
						}
					},
					error: function(result) {
						$(form).removeAttr('isSubmitting')
						$(form).parent().parent().prepend(result.responseText);
					}
				});
			});
				// When content of module is loaded, apply datepicker plugin if its necessary
			$moduleContainer.find('.datepicker').each(function(index, datepicker) {
				$(datepicker).datetimepicker($(datepicker).hasClass('withTime') ? App.dateTimePickerSettings : App.datePickerSettings);
			});
			$moduleContainer.find('.datepickerIcon').on('click', function(){$(this).parent().find('.datepicker').trigger('focus')});
			$moduleContainer.find('select:not(".input-mini")').each(function(key, element) {
				var $element = $(element);
				if ($element.parents('form').length) {
					$element.chosen({width: '100%'});
				} else {
					$element.chosen();
				}
			});
			$moduleContainer.find('input').applyInputMasks();
			$moduleContainer.find('.countrySelect').countrySelect();
			if ($primary != undefined) {
				$primary.controlPrimary();
			}

			// modal customization
			if ($moduleContainer.parents('.modal').length) {

				if ($moduleContainer.find('.modal-header.custom').length > 0 && $moduleContainer.parent().find('.modal-header:not(.custom)').length > 0) {
					var $customHeader = $moduleContainer.find('.modal-header.custom')
					$moduleContainer.parent().find('.modal-header:not(.custom)').replaceWith($customHeader);
					$customHeader.removeClass('custom');
				}
				if ($moduleContainer.find('.modal-footer.custom').length > 0) {
					$moduleContainer.parent().find('.modal-footer:last').addClass('hide');
				} else {
					$moduleContainer.parent().find('.modal-footer').removeClass('hide');

					var $saveButton = $moduleContainer.parent().find('.modal-footer .btn-save');
					var $closeButton = $moduleContainer.parent().find('.modal-footer .btn-close');
					if ($moduleContainer.parent().find('input[name="iscomplete"]').length > 0) {
						$moduleContainer.parent().find('input[name="iscomplete"]').each(function(k,isCompleteElement) {
							var $form = $(isCompleteElement).parents('form:first');
							$form.find('input[type="submit"],button[type="submit"],.form-actions').remove();

							$saveButton.show();
							$saveButton.unbind('click');
							$saveButton.on('click', function(event) {
								$form.submit() ;
							})
							$closeButton.hide()
						});
					} else {
						$closeButton.show();
						$saveButton.hide();
					}
				}
			}
		},

		prepareUrl: function (object) {
			var urlParams = "";
			if (object != '') {
				var first = true;
				for (var key in object) {
					if (object[key] != "undefined") {
						urlParams += (first ? '?' : '&') + key + "=" + object[key];
						first = false;
					}
				}
			}
			return urlParams;
		},

		startAjaxRequest: function() {
			$('body').addClass('ajax-loading');
		},

		beforeSubmit: function(formData, form) {
			if (form != undefined) {
				if (!form.attr('isSubmitting') ) {
					form.attr('isSubmitting', true);
					return true;
				} else {
					return false;
				}
			}
			return true;
		},
		finishedAjaxRequest: function() {
			$('body').removeClass('ajax-loading');
		}

	});

}).call(this);
