(function() {
	'use strict';
	function verifyRecoveryTimeCheckbox() {
		if ($('#recoveryDateUnknown').attr('checked', 'checked')) {
			$('#estimatedRecoveryDate').val('')
		}
	}
	function verifyRecoveryTimeInput() {
		if ($('#estimatedRecoveryDate').val() != '') {
			$('#recoveryDateUnknown').prop('checked', false)
		} else {
			$('#recoveryDateUnknown').prop('checked', true)
		}
	}
	$('#estimatedRecoveryDate').on('change', verifyRecoveryTimeInput).trigger('change');
	$('#recoveryDateUnknown').on('click', verifyRecoveryTimeCheckbox);

}).call(this);