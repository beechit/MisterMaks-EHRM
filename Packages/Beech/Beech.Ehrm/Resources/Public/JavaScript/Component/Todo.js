define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.Todo = Ember.Object.extend();
			App.Todo.reopenClass({
				find: function() {
					return [
						{
							'label': 'high',
							'items': [
								{label: 'test'}
							]
						},
						{
							'label': 'normal',
							'items': [
								{label: 'test'}
							]
						}
					];
				}
			});
		});
	}
);