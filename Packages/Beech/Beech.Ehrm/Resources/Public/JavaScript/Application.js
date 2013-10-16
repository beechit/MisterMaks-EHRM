(function() {
	'use strict';

	App.ApplicationView = Ember.View.extend();
	App.ApplicationView.reopen({
		classNames: ['full']
	});

	App.AdministrationMenuView = Ember.View.extend({
		templateName: 'administration/menu'
	});

	App.ApplicationController = Ember.Controller.extend();
	App.ApplicationController = App.ApplicationController.reopen({
			// update current RouteName in App
		updateCurrentRouteName: function() {
			App.set('currentRouteName', this.get('currentRouteName'));

		}.observes('currentRouteName'),

		updateCurrentPath: function() {
			App.set('currentPath', this.get('currentPath'));
			App.set('customBreadcrumbElements', []);
		}.observes('currentPath'),

		breadcrumbElements: [],

		updateBreadcrumbs: function() {

			var regexContent = 'BeechEhrm|BeechParty|BeechDocument|BeechTask|BeechAbsence|BeechCLA|BeechChart';
			// reset breadcrumbs
			this.set('breadcrumbElements', []);

			// prepare new breadcrumbs
			var breadcrumbElements = [];
			var breadcrumbTemp = this.get('currentPath').split('.');
			// exception
			if (breadcrumbTemp[0] == 'index' && breadcrumbTemp[1] == 'index') {
				breadcrumbTemp[0] = 'Dashboard';
				breadcrumbTemp[1] = '';
			}

			// check if there are custom breadcrumb elements
			var customBreadcrumbElements = App.get('customBreadcrumbElements');
			for (var i = 0; i < customBreadcrumbElements.length ; i++) {
				if (customBreadcrumbElements[i].position >= 0) {
					breadcrumbTemp[customBreadcrumbElements[i].position] = customBreadcrumbElements[i].value;
				} else {
					breadcrumbTemp.push(customBreadcrumbElements[i].value);
				}
			}
			for (var i = 0; i < breadcrumbTemp.length ; i++) {

				var element = breadcrumbTemp[i];
				if (element != "#" && element != '' && element != 'index' && element != 'show' && element != 'edit' && element != 'list' && element != 'overview') {
					// remove package name
					element = element.replace(new RegExp('^('+regexContent+')', 'g'), '')
					element = {
						value: element.replace(/^(.)|\s(.)/g, function($1){ return $1.toUpperCase( ); }),
						isLast: false
					}
					breadcrumbElements.push(element)
				}
				//update content of replacements regex
				regexContent = breadcrumbTemp[i] + '|'+ regexContent;
			}

			// mark element as last, to not display divider after it in handlebar
			breadcrumbElements[breadcrumbElements.length - 1].isLast = true;

			this.set('breadcrumbElements', breadcrumbElements);
			App.set('breadcrumbElements', this.get('breadcrumbElements')) ;

			// update title
			this.updateTitle() ;

		}.observes('currentPath', 'App.customBreadcrumbElements'),

		title: 'Mister Maks',

		updateTitle: function() {
			var breadcrumbTemp = this.get('breadcrumbElements');
			this.set('title', this.get('breadcrumbElements')[0].value + ' :: Mister Maks')
			document.title = this.get('title');
		},

		init: function() {

				// Add modal-ajax toggle for rendering wizards in modal boxes
			$(document).on('click.modal.data-api', '[data-toggle="modal-ajax"]', function (e) {
				var $this = $(this),
					href = $this.attr('href'),
					$target = $($this.attr('data-target') || (href && href.replace(/\.*(?=#[^\s]+$)/, '')));
				e.preventDefault();

				$target.removeClass('modal-small modal-large');
				if ($this.attr('modal-size') != '') {
					$target.addClass('modal-' + $this.attr('modal-size'));
				}

				$target.removeAttr('data-reload');
				if ($this.attr('data-reload')) {
					$target.attr('data-reload', $this.attr('data-reload'));
				}

				$target.find('.modal-content').html('<p><i class="icon-spin icon-spinner icon-2x muted"></i></p>');
				App.ModuleHandler.loadUrl(href, $this.attr('data-target') + ' .modal-content');

				$target
					.modal({backdrop: 'static'})
					.one('hide', function () {
						$this.focus();
					});
			});
		},

		ready: function() {
			$('[rel=tooltip]').tooltip();
			$('[rel=popover]').popover();
		}
	});
}).call(this);
