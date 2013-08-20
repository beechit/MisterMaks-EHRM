(function() {
	'use strict';

	App.BeechCLAContractArticleView = Ember.View.extend(App.AjaxModuleViewMixin);
	App.BeechCLAContractArticleIndexView = App.BeechCLAContractArticleView.extend();
	App.BeechCLAContractArticleNewView = App.BeechCLAContractArticleView.extend();
	App.BeechCLAContractArticleEditView = App.BeechCLAContractArticleView.extend();
	App.BeechCLAContractArticleShowView = App.BeechCLAContractArticleView.extend();
	App.BeechCLAContractArticleDeleteView = App.BeechCLAContractArticleView.extend();

}).call(this);