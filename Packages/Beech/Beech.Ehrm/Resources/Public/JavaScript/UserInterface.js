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
					var $modal = jQuery('<div></div>').addClass('modal fade').attr('id', 'entityModal');
					jQuery('section.container').append($modal)
					jQuery('a[data-toggle=modal]').live('click', function() {
						var target = jQuery(this).attr('data-target');
						var url = jQuery(this).attr('href');
						var content = jQuery('<iframe onLoad="contentLoadFinisher()"></iframe>').addClass('modal-body-content').attr('src',url);
						jQuery(target)
							.html(jQuery('.loading').html()) // Set default loading content
							.find('.modal-body')
							.html(content);
					});
				}
			});
		}
	};
});