(function() {
	'use strict';

	App.ApplicationView = Ember.View.extend();
	App.ApplicationView.reopen({
		classNames: ['full']
	});

	App.AdministrationMenuView = Ember.View.extend({
		templateName: 'administration_menu'
	});

	App.ApplicationController = Ember.Controller.extend();
	App.ApplicationController = App.ApplicationController.reopen({
		title: 'Mister Maks',

		init: function() {
			$('.datepicker').on('click', function() {
				$(this).datepicker({showOn:'focus'})
					.on('changeDate', function() {
						$(this).datepicker('hide');
					})
					.focus();
			});

				// Add modal-ajax toggle for rendering wizards in modal boxes
			$(document).on('click.modal.data-api', '[data-toggle="modal-ajax"]', function (e) {
				var $this = $(this),
					href = $this.attr('href'),
					$target = $($this.attr('data-target') || (href && href.replace(/\.*(?=#[^\s]+$)/, '')));
				e.preventDefault();

				if ($this.attr('modal-size') != '') {
					$target.addClass('modal-' + $this.attr('modal-size'));
				}

				$target.find('.modal-header').addClass('hide');
				$target.find('.modal-body').html('<p><i class="icon-spin icon-spinner icon-2x muted"></i></p>');
				$target.find('.modal-footer').addClass('hide');

				App.ModuleHandler.loadUrl(href, $this.attr('data-target') + ' .modal-body');

				$target
					.modal()
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
