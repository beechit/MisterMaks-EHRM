define(
	['jquery', 'emberjs', 'Beech.Ehrm/Library/jit/jit'],
	function (jQuery, Ember) {
		return Ember.Object.create({
			_tree: null,
			container: 'social-infovis',
			orientation: 'top',
			constrained: false,
			levelsToShow: 100,
			//id of viz container element
			injectInto: this.container,
			//set duration for the animation
			duration: 100,
			//set distance between node and its children
			levelDistance: 30,
			//enable panning
			navigation: {
				enable: true,
				panning: true
			},
			node: {
				height: 106,
				width: 176,
				type: 'rectangle',
				color: '#3F79B5',
				overridable: true
			},
			edge: {
				type: 'bezier',
				overridable: true,
				lineWidth: 3,
				color: '#3F79B5'
			},
			setContainer: function (container) {
				this.set('container', container)
			},
			initialize: function () {
				var that = this;
				this._tree = new $jit.ST({
					orientation: that.get('orientation'),
					constrained: that.get('constrained'),
					levelsToShow: that.get('levelsToShow'),
					//id of viz container element
					injectInto: that.get('container'),
					//set duration for the animation
					duration: that.get('duration'),
					//set distance between node and its children
					levelDistance: that.get('levelDistance'),
					//enable panning
					Navigation: that.get('navigation'),
					Node: that.get('node'),
					Edge: that.get('edge'),
					onCreateLabel: function (label, node) {
						var $label = $(label);
						var $picture = $('<div class="node-picture" style="float: left;"><img src="' + node.data.picture + '"/></div>');
						var $content = $('<div class="node-text">' + node.name +
							'<br/>' + node.data.function +
							'<br/>' + node.data.email +
							'<br/>' + node.data.phone +
							'</div>');
						$label.append($picture).append($content);
						$label.attr('id', node.id)
						$label.on('click', function () {
							that._tree.onClick(node.id, {
								Move: {
									enable: false
								}
							});
						});
					},

					onBeforePlotNode: function (node) {
						if (node.selected) {
							node.data.$color = "#D4247A";
						}
						else {
							delete node.data.$color;
						}
					},

					onBeforePlotLine: function (adj) {
						if (adj.nodeFrom.selected && adj.nodeTo.selected) {
							adj.data.$color = "#D4247A";
							adj.data.$lineWidth = 3;
						}
						else {
							delete adj.data.$color;
							delete adj.data.$lineWidth;
						}
					}
				});
			},
			load: function (url) {
				var that = this;
				$.getJSON(url, function (json) {
					//load json data
					that.get('_tree').loadJSON(json);
					//compute node positions and layout
					that.get('_tree').compute();
					//optional: make a translation of the tree
					that.get('_tree').geom.translate(new $jit.Complex(-200, 0), "current");
					//emulate a click on the root node.
					that.get('_tree').onClick(that.get('_tree').root);
				});
			}
		});
	}
);
