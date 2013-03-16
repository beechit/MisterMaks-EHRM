(function() {
	'use strict';

	App.ApplicationView = Ember.View.extend();
	App.ApplicationView.reopen({
		classNames: ['full']
	});
	App.ApplicationController = Ember.Controller.extend();
	App.ApplicationController = App.ApplicationController.reopen({
		title: 'Mister Maks',

		init: function() {
			$('.datepicker').live('click', function() {
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

				$target.find('.modal-body').html('<p><i class="icon-spin icon-spinner icon-4x muted"></i></p>');

				App.ModuleHandler.loadUrl(href, '#modal-body-only .modal-body');

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
