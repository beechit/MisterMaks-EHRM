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
		var selected = $(this).find('option:selected');

		if (selected.attr('expiration')) {
			$('#documentExpiration').parents('.control-group').removeClass('hide');
		} else {
			$('#documentExpiration').parents('.control-group').addClass('hide').val('');
		}
		if (selected.attr('number')) {
			$('#documentNumber').parents('.control-group').removeClass('hide');
		} else {
			$('#documentNumber').parents('.control-group').addClass('hide').val('');
		}
		if (selected.attr('period')) {
			$('#documentPeriod').parents('.control-group').removeClass('hide');
		} else {
			$('#documentPeriod').parents('.control-group').addClass('hide').val('');
		}
		if (selected.attr('year')) {
			$('#documentYear').parents('.control-group').removeClass('hide');
		} else {
			$('#documentYear').parents('.control-group').addClass('hide').val('');
		}
	})
	$('select.documentType').trigger('change')
}).call(this);