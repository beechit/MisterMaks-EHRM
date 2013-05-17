(function() {
	'use strict';

	$(document).ready(function() {
			// Modal
		$('.login').on('click', function(event) {
			event.preventDefault();

			var url = $(this).attr('href');

				//Use AJAX get function to fetch the html
			$.get(url, function(html) {
				var content = $('#content', html).html();
				$('#modal-body-only .modal-body').html(content);
				$('#modal-body-only #username').focus();
			});
		});

			// Reset the stage so on reloads we don't get old data
		$('#modal-body-only').on('hidden', function () {
			$('#modal-body-only .modal-body').html('<p><i class="icon-spin icon-spinner icon-3x muted"></i></p>');
		});

			// Make sure clicks stays in the modal
		$('#modal-body-only form').updateModal();
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
					success: function(data) {
						var result = $('#content', data).html();

							// Redirect upon successful login
						if (typeof(result) === 'undefined') {
							window.location = $('base').text();
						}

						target.html(result);
						$('#modal-body-only form').updateModal();
					}
				});

				event.preventDefault();
			});
		};
	})(jQuery);
}).call(this);