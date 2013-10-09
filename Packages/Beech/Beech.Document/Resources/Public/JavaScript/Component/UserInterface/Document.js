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
}).call(this);