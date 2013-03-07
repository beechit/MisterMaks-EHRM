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
						$(this).datepicker('hide')
					})
					.focus();
			});

			$('#modal-body-only').on('DOMSubtreeModified', function() {
				var $that = $(this);

				$(this).find('form').each(function() {
					$(this).attr('action', App.ModuleHandler.jsonpifyUrl($(this).attr('action')));
				});

				$(this).find('form').ajaxForm({
					dataType: 'jsonp',
					success: function(result) {
						App.ModuleHandler.loadContent(result.html, $that.find('.modal-body'));
					}
				});
			});
		},

		ready: function() {
			$('[rel=tooltip]').tooltip();
			$('[rel=popover]').popover();
		}
	});
}).call(this);
