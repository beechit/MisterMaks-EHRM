(function() {
	'use strict';

	$(document).ready(function() {
			// Modal
		$('.login').on('click', function(event) {
			event.preventDefault();

			var url = $(this).attr('href');

				// Use AJAX get function to fetch the html
			$('#modal .modal-content').html('<p><i class="icon-spin icon-spinner icon-4x muted"></i></p>');
			$('#modal').modal();
			$('#modal .modal-content').load(url, function() {
				$('#modal form').updateModal();
				$('#modal #username').focus();
			});
		});
	});

	(function($) {
		$.fn.updateModal = function() {
			$(this).on('submit', function(event) {
				var that = $(this);
				if (!$(this).attr('isSubmitting')) {
					$(this).attr('isSubmitting', true);

					$('<i class="icon-spin icon-spinner icon-large muted"></i>').appendTo('.form-actions');
					var form = $(this);
					var target = $(form.attr('data-target'));

					$.ajax({
						type: form.attr('method'),
						url: form.attr('action'),
						data: form.serialize(),
						success: function(result) {
							// Redirect upon successful login
							if (!result || result == 'ok') {
								window.location = $('base').text();
							} else {
								target.html(result);
								$('#modal form').updateModal();
							}
							that.removeAttr('isSubmitting')
						}
					});
				}



				event.preventDefault();
			});
		};
	})(jQuery);
}).call(this);