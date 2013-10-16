(function() {
	'use strict';
	function verifyDocument() {
		if ($('.documentAttachment').val() != '' && $('.documentName').val() != '') {
			$('.info-documentAttachment').hide();
			$('.control-documentSubmit').show();
		} else {
			$('.info-documentAttachment').show();
			$('.control-documentSubmit').hide();
		}
	}
	$('.form-newDocument').find('.documentAttachment').on('change', verifyDocument);
	$('.form-newDocument').find('.documentName').on('blur keyup', verifyDocument);

	$('select.documentType').on('change', function() {
		if ($(this).find('option:selected').attr('expiration')) {
			$('#expiration').parents('.control-group').removeClass('hide');
		} else {
			$('#expiration').parents('.control-group').addClass('hide').val('');

		}
	})
	$('select.documentType').trigger('change')
}).call(this);