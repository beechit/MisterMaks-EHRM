(function() {
	'use strict';

	App.ModelMixin = Ember.Mixin.create({

		didLoad: function() {
			console.log('Task: ' + this.get('description') + ' finished loading.');
		},

		isNew: function() {
			console.log('new');
			App.store.commit();
		}.observes('completed'),

		isSaving: function() {
			console.log('saving');
			App.store.commit();
		}.observes('completed'),

		isDirty: function() {
			console.log('dirty');
			App.store.commit();
		}.observes('completed'),

		isDeleted: function() {
			console.log('delete');
			App.store.commit();
		}.observes('completed'),

		isError: function() {
			console.log('error');
		}.observes('completed')

	});

}).call(this);