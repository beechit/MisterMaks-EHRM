(function() {
	'use strict';

	$.fn.extend({
		controlPrimary: function() {
			return this.each(function(input_field) {
				var $this;
				$this = $(this);
				$this.on('click', function() {
					if ($this.is(':checked')) {
							// elementId should be in format primary-[elementName]-[elementIdentifier]
						var elementId = $this.attr('id');
						var parsed = (elementId+'').split('-');
						if (parsed[1] != undefined) {
							var elementName = parsed[1];
							var elementIdentifier = parsed[2];
							var $parent = $this.parents('.'+elementName+'Section').parent();
							var type = $this.parents('#toggle'+elementIdentifier).find('.'+elementName+'Type').val();
								// checking all elements in section if have the same type
							$('.'+elementName+'Section').each(function(index, elem) {
								if(type != undefined && $(elem).find('.'+elementName+'Type').val() == type) {
										// unselect checkbox 'primary'
									$(elem).find('.primary').prop('checked',false);
										// remove icon
									$(elem).find('.icon-check').remove();
								}
							});
								// set icon end checkbox for new primary
							$parent.find('.primaryIcon').html('<i class="icon-check"></i>');
							$this.prop('checked', true);
								// force to trigger save action
							$parent.find('button[name=action][value=update]').trigger('click');
						}
					}
				})
			});
		}
	});

}).call(this);