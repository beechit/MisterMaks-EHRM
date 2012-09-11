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
					var _self = this;
					var $modal = jQuery('<div></div>').addClass('modal fade').attr('id', 'entityModal');
					jQuery('section.container').append($modal);
					jQuery('a[data-toggle=modal]').live('click', function() {
						var target = jQuery(this).attr('data-target');
						var url = jQuery(this).attr('href');
						var content = jQuery('<iframe></iframe>').addClass('modal-body-content').attr('src',url);
						jQuery(target)
							.html(jQuery('.loading').html()) // Set default loading content
							.find('.modal-body')
							.html(content);
						$(content).load(function() {
							_self.contentLoadFinisher(this)
						});
					});
				},

				getContentTitle: function() {
					return $('.modal-body-content').contents().find('legend').html();
				},

				contentLoadFinisher: function(iframe) {
					var title = this.getContentTitle();
					var modal = $(iframe).parents('.modal');
					modal.find('.modal-body-content').contents().find('legend').hide();
					this.setModalTitle(modal, title);
				},

				setModalTitle: function(modal, title) {
					var titleContainer = modal.find('.modal-header h3');
					titleContainer.html(title);
					return;
				}

			});
		}
	};
});