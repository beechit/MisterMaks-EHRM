(function() {
	'use strict';

	$.fn.extend({
		controlPrimary: function() {
			return this.each(function(input_field, el) {
				console.log('do it', el);
				var $this;
				$this = $(this);
					// elementId should be in format primary-[elementName]-[elementIdentifier]
				var elementId = $this.attr('id');
				var parsed = (elementId+'').split('-');
				if ($this.is(':checked')) {
					if (parsed[1] != undefined) {
						var elementName = parsed[1];
						var elementIdentifier = parsed[2];
						var $parent = $this.parents('.'+elementName+'Section').parent();
						var type = $this.parents('#toggle'+elementIdentifier).find('.'+elementName+'Type').val();
						$('.'+elementName+'Section').each(function(index, elem) {
							if(type != undefined && $(elem).find('.'+elementName+'Type').val() == type && $(elem).attr('id') != ('update'+elementIdentifier)) {
								// unselect checkbox 'primary'
								$(elem).find('.primary').prop('checked',false).parent().removeClass('hide');
								// remove icon
								$(elem).find('.primaryIcon').html('');
								$(elem).find('.primaryIcon').next().find('.btn-remove').removeClass('hide');
							}
						});
					}
				}
			});
		}
	});

}).call(this);