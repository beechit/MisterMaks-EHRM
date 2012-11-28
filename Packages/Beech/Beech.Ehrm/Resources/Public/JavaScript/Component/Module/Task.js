define (
	['emberjs'],
	function (Ember) {
		MM.init.preInitialize.push(function() {
			App.ModuleTaskController = Ember.Controller.extend({
				content: '',
				loadContent: function() {
					$.ajax({
						context: this,
						url: 'some url met csrf token thingy vanaf die ene href daar rechtsboven selector',
						format: 'jsonp',
						success: function(result) {
							console.log('success', result);
							this.set('content', 'dat was leuk');
						}
					})
					this.set('content', '<section class="container-fluid"><p>No todo\'s</p><div><div class="form-horizontal well"><input id="description" type="text" name="newToDo[description]" placeholder="Task"/><select name="newToDo[priority]"><option value="100">veryHigh</option><option value="75">high</option><option value="50">normal</option><option value="25">low</option></select><a class="btn btn-primary">Create</a></div></div></section>');
				}
			});
			App.ModuleTaskView = Ember.View.extend({
				templateName: 'module-task',
				didInsertElement: function() {
					this.get('controller').loadContent();
				}
			});
		});
	}
);