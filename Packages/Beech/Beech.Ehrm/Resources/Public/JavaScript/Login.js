(function() {
	'use strict';

	$(document).ready(function() {
			// Modal
		$('.login').on('click', function(event) {
			event.preventDefault();

			var url = $(this).attr('href');

				// Use AJAX get function to fetch the html
			$('#modal-body-only').modal();
			$('#modal-body-only .modal-body').load(url, function() {
				// Make sure clicks stays in the modal
				$('#modal-body-only form').updateModal();
				$('#modal-body-only #username').focus();
			});
		});

			// Reset the stage so on reloads we don't get old data
		$('#modal-body-only').on('hidden', function () {
			$('#modal-body-only .modal-body').html('<p><i class="icon-spin icon-spinner icon-4x muted"></i></p>');
		});

	});

	(function($) {
		$.fn.updateModal = function() {
			$(this).on('submit', function(event) {
				$('<i class="icon-spin icon-spinner icon-large muted"></i>').insertAfter('button');
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
							$('#modal-body-only form').updateModal();
						}
					}
				});

				event.preventDefault();
			});
		};
	})(jQuery);
}).call(this);