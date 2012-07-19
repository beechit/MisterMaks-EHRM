$(document).ready(function() {
	$('a[data-toggle=modal]').live('click', function() {
		var target = $(this).attr('data-target');
		var url = $(this).attr('href');
		$(target)
			.html($('.loading').html()) // Set default loading content
			.load(url);
	});
});
