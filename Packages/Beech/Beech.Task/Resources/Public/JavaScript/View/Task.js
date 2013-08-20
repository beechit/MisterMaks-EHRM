(function() {
	'use strict';

	App.BeechTaskTaskView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechTaskTaskIndexView = App.BeechTaskTaskView.extend();
	App.BeechTaskTaskShowView = App.BeechTaskTaskView.extend();
	App.BeechTaskTaskEditView = App.BeechTaskTaskView.extend();

}).call(this);