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

				$target.removeClass('modal-small modal-large');
				if ($this.attr('modal-size') != '') {
					$target.addClass('modal-' + $this.attr('modal-size'));
				}

				$target.removeAttr('data-reload');
				if ($this.attr('data-reload')) {
					$target.attr('data-reload', $this.attr('data-reload'));
				}

				$target.find('.modal-content').html('<p><i class="icon-spin icon-spinner icon-2x muted"></i></p>');
				App.ModuleHandler.loadUrl(href, $this.attr('data-target') + ' .modal-content');

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
