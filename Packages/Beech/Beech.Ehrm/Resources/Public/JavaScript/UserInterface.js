define(['jquery', 'emberjs'], function(jQuery, Ember) {
	return {
		navigation: function() {
			return Ember.Object.create({
				initialize: function() {
				}
			});
		},

		modal: function() {
			return Ember.Object.create({
				initialize: function() {
					jQuery('a[data-toggle=modal]').live('click', function() {
						var target = jQuery(this).attr('data-target');
						var url = jQuery(this).attr('href');
						jQuery(target)
							.html(jQuery('.loading').html()) // Set default loading content
							.load(url);
					});
				}
			});
		}
	};
});