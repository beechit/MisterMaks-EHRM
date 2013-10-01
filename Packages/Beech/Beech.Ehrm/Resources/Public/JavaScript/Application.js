(function() {
	'use strict';

	App.ApplicationView = Ember.View.extend();
	App.ApplicationView.reopen({
		classNames: ['full']
	});

	App.AdministrationMenuView = Ember.View.extend({
		templateName: 'administration/menu'
	});

	App.ApplicationController = Ember.Controller.extend();
	App.ApplicationController = App.ApplicationController.reopen({

			// update current RouteName in App
		updateCurrentRouteName: function() {
			App.set('currentRouteName', this.get('currentRouteName'));
		}.observes('currentRouteName'),

		title: 'Mister Maks',

		init: function() {

				// Add modal-ajax toggle for rendering wizards in modal boxes
			$(document).on('click.modal.data-api', '[data-toggle="modal-ajax"]', function (e) {
				var $this = $(this),
					href = $this.attr('href'),
					$target = $($this.attr('data-target') || (href && href.replace(/\.*(?=#[^\s]+$)/, '')));
				e.preventDefault();

				if ($this.attr('modal-size') != '') {
					$target.addClass('modal-' + $this.attr('modal-size'));
				}

				$target.removeAttr('data-reload');
				if ($this.attr('data-reload')) {
					$target.attr('data-reload', $this.attr('data-reload'));
				}

				$target.find('.modal-header').addClass('hide');
				$target.find('.modal-body').html('<p><i class="icon-spin icon-spinner icon-2x muted"></i></p>');
				$target.find('.modal-footer').addClass('hide');
				if ($target.find('.modal-footer').find('.btn[data-action="save"]')) {
					if ($this.attr('data-reload')) {
						$target.find('.modal-footer').find('.btn[data-action="save"]').attr('data-reload', $this.attr('data-reload'))
					} else {
						$target.find('.modal-footer').find('.btn[data-action="save"]').removeAttr('data-reload')
					}
				}

				App.ModuleHandler.loadUrl(href, $this.attr('data-target') + ' .modal-body');

				$target
					.modal({backdrop: 'static'})
					.one('hide', function () {
						$this.focus();
					});
			});
		},

		ready: function() {
			$('[rel=tooltip]').tooltip();
			$('[rel=popover]').popover();
		}
	});
}).call(this);
