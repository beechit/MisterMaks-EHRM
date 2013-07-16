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
				} else if ($this.hasClass('capitalize')) {
					$this.keyup(function(evt){
						var txt = $(this).val();
						$(this).val(txt.replace(/^(.)|\s(.)/g, function($1){ return $1.toUpperCase( ); }));
					});
				} else if ($this.hasClass('uppercase')) {
					$this.keyup(function(evt){
						var txt = $(this).val();
						$(this).val(txt.toUpperCase( ));
					});
				}
			});
		}
	});

}).call(this);