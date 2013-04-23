define(['jquery', 'emberjs', 'form'], function ($, Ember) {
	return {
		navigation: function () {
			return Ember.Object.create({
				initialize: function () {
				}
			});
		},

		modal: function () {
			return Ember.Object.create({

				modalId: '',

				initialize: function () {
					var that = this;
					that.createModalBox();
					$('a[data-toggle=modal]').on('click', function () {
						var target = $(this).attr('data-target');
						var url = $(this).attr('href');
						var modal = that.createModalBox(target);
						$(target)
							.html($('.loading').html()) // Set default loading content
						$.ajax({
							type: 'POST',
							url: url,
							data: {}
						}).done(function (response) {
								that.contentLoadFinisher($(target), response);
							});
					});
				},

				process: function (response, statusText, xhr, $form) {
					var target = '#' + $($form).parents('.modal').attr('id');
					that.contentLoadFinisher($(target), response);
				},

				isCreated: function (modalId) {
					return ($(modalId).length > 0) ? true : false;
				},

				createModalBox: function (modalId) {
					if (modalId === undefined) {
						modalId = this.getModalId();
					}
					if (!this.isCreated(modalId)) {
						var modal = $('<div></div>')
							.addClass('modal fade')
							.attr('id', modalId.replace('#', ''));
						$('section.container').append(modal);
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
					return $('#' + this.getModalId()).find('legend').html();
				},

				contentLoadFinisher: function (modal, content) {
					if (this.hasRedirection(content)) {
						var urlRegex = /([\d+];url=)((https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?)/;
						var url = $('<div></div>').append(content).find('meta[http-equiv="refresh"]').attr('content').replace(urlRegex, '$2');
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
						modal.find('form').ajaxForm({success: that.process});
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
					if ($('<div></div>').append(content).find('meta[http-equiv="refresh"]').length === 1) {
						return true;
					} else {
						return false;
					}
				}

			});
		}
	};
});