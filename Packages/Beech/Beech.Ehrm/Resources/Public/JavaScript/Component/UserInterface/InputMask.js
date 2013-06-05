(function() {
	'use strict';

	$.fn.extend({
		applyInputMasks: function() {
			return this.each(function(input_field) {
				var $this;
				$this = $(this);
				if ($this.hasClass('phone')) {
					$this.inputmask("99-99999999", { "placeholder": "06_________" });
				} else if ($this.hasClass('postal')) {
					$this.inputmask("9999 AA");
				} else if ($this.hasClass('bsn')) {
					$this.inputmask("9999.99.999");
				}
			});
		}
	});

}).call(this);