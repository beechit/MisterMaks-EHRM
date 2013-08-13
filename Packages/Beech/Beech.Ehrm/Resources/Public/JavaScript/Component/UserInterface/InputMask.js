(function() {
	'use strict';

	$.fn.extend({
		applyInputMasks: function(options) {
			return this.each(function(input_field) {
				var $this;
				$this = $(this);
				if ($this.hasClass('mobilephone')) {
					$this.inputmask("99 99999999", { "placeholder": "06 ________" });
				} else if ($this.hasClass('phone')) {
					$this.inputmask("999 9999999", { "placeholder": "0__ ________" });
				} else if ($this.hasClass('postal')) {
					var inputMaskOptions = {};
					if (options != undefined && options.country != undefined) {
						// TODO: Read this from config file ? Probably there will be more masks...
						switch (options.country) {
							case 'NL':
								inputMaskOptions = {mask: "9999 AA", placeholder: "_"};
								break;
							case 'PL':
								inputMaskOptions = {mask: "99-999", placeholder: "_"};
								break;
							default:
								inputMaskOptions = 'remove';
						}
						$this.inputmask(inputMaskOptions);
					}
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