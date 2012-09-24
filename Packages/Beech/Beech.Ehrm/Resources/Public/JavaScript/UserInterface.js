define(['jquery', 'emberjs', 'form'], function (jQuery, Ember) {
	return {
		navigation: function () {
			return Ember.Object.create({
				initialize: function () {
				}
			});
		},

		modal: function () {
			var _self;
			return Ember.Object.create({

				modalId: '',

				initialize: function () {
					_self = this;
					_self.createModalBox();
					jQuery('a[data-toggle=modal]').live('click', function () {
						var target = jQuery(this).attr('data-target');
						var url = jQuery(this).attr('href');
						var modal = _self.createModalBox(target);
						jQuery(target)
							.html(jQuery('.loading').html()) // Set default loading content
						jQuery.ajax({
							type: 'POST',
							url: url,
							data: {}
						}).done(function (response) {
								_self.contentLoadFinisher(jQuery(target), response);
							});
					});
				},

				process: function (response, statusText, xhr, $form) {
					var target = '#' + jQuery($form).parents('.modal').attr('id');
					_self.contentLoadFinisher(jQuery(target), response);
				},

				isCreated: function (modalId) {
					return (jQuery(modalId).length > 0) ? true : false;
				},

				createModalBox: function (modalId) {
					if (modalId === undefined) {
						modalId = this.getModalId();
					}
					if (!this.isCreated(modalId)) {
						var modal = jQuery('<div></div>')
							.addClass('modal fade')
							.attr('id', modalId.replace('#', ''));
						jQuery('section.container').append(modal);
					}
					return modal;
				},

				getModalId: function () {
					if (this.get('modalId') === '') {
						this.set('modalId', 'entityModal')
					}
					return this.get('modalId');
				},

				getContentTitle: function () {
					return jQuery('#' + this.getModalId()).find('legend').html();
				},

				contentLoadFinisher: function (modal, content) {
					if (this.hasRedirection(content)) {
						var urlRegex = /([\d+];url=)((https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?)/;
						var url = jQuery('<div></div>').append(content).find('meta[http-equiv="refresh"]').attr('content').replace(urlRegex, '$2');
						if (url !== '') {
							window.location = url;
						}
					} else {
						// Set content for modal box body
						modal.find('.modal-body')
							.hide()// Hide until processing is finished
							.html(content);
						// Set title for modal box
						var title = this.getContentTitle();
						modal.find('legend').hide();
						this.setModalTitle(modal, title);
						// Set as ajax form
						modal.find('form').ajaxForm({success: _self.process});
						// Processing is finished, so show content
						modal.find('.modal-body').show();
					}
				},

				setModalTitle: function (modal, title) {
					var titleContainer = modal.find('.modal-header h3');
					titleContainer.html(title);
					return;
				},

				hasRedirection: function (content) {
					if (jQuery('<div></div>').append(content).find('meta[http-equiv="refresh"]').length === 1) {
						return true;
					} else {
						return false;
					}
				}

			});
		}
	};
});