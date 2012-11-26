define(
	[
		'jquery',
		'emberjs',
		'Beech.Ehrm/Library/rgraph/js/RGraph.common.core',
		'Beech.Ehrm/Library/rgraph/js/RGraph.common.annotate',
		'Beech.Ehrm/Library/rgraph/js/RGraph.common.context',
		'Beech.Ehrm/Library/rgraph/js/RGraph.common.dynamic',
		'Beech.Ehrm/Library/rgraph/js/RGraph.common.effects',
		'Beech.Ehrm/Library/rgraph/js/RGraph.common.key',
		'Beech.Ehrm/Library/rgraph/js/RGraph.common.resizing',
		'Beech.Ehrm/Library/rgraph/js/RGraph.common.tooltips',
		'Beech.Ehrm/Library/rgraph/js/RGraph.common.zoom',
		'Beech.Ehrm/Library/rgraph/js/RGraph.bar',
		'Beech.Ehrm/Library/rgraph/js/RGraph.bipolar',
		'Beech.Ehrm/Library/rgraph/js/RGraph.cornergauge',
		'Beech.Ehrm/Library/rgraph/js/RGraph.drawing.circle',
		'Beech.Ehrm/Library/rgraph/js/RGraph.drawing.clock',
		'Beech.Ehrm/Library/rgraph/js/RGraph.drawing.image',
		'Beech.Ehrm/Library/rgraph/js/RGraph.drawing.marker1',
		'Beech.Ehrm/Library/rgraph/js/RGraph.drawing.marker2',
		'Beech.Ehrm/Library/rgraph/js/RGraph.drawing.rect',
		'Beech.Ehrm/Library/rgraph/js/RGraph.fuel',
		'Beech.Ehrm/Library/rgraph/js/RGraph.funnel',
		'Beech.Ehrm/Library/rgraph/js/RGraph.gantt',
		'Beech.Ehrm/Library/rgraph/js/RGraph.gauge',
		'Beech.Ehrm/Library/rgraph/js/RGraph.hbar',
		'Beech.Ehrm/Library/rgraph/js/RGraph.hprogress',
		'Beech.Ehrm/Library/rgraph/js/RGraph.led',
		'Beech.Ehrm/Library/rgraph/js/RGraph.line',
		'Beech.Ehrm/Library/rgraph/js/RGraph.meter',
		'Beech.Ehrm/Library/rgraph/js/RGraph.modaldialog',
		'Beech.Ehrm/Library/rgraph/js/RGraph.odo',
		'Beech.Ehrm/Library/rgraph/js/RGraph.pie',
		'Beech.Ehrm/Library/rgraph/js/RGraph.radar',
		'Beech.Ehrm/Library/rgraph/js/RGraph.rose',
		'Beech.Ehrm/Library/rgraph/js/RGraph.rscatter',
		'Beech.Ehrm/Library/rgraph/js/RGraph.scatter',
		'Beech.Ehrm/Library/rgraph/js/RGraph.thermometer',
		'Beech.Ehrm/Library/rgraph/js/RGraph.vprogress',
		'Beech.Ehrm/Library/rgraph/js/RGraph.waterfall'
	],
	function($, Ember) {
		return Ember.Object.create({
			id: '',
			placeholder: 'body',
			width: 200,
			height: 150,
			data: [],
			type: 'Bar',
			properties: {},
			_chart: null,

			initialize: function(options) {
				if (options !== undefined) {
					for (key in options) {
						this.set(key, options[key]);
					}
				}
			},
			initChart: function() {
				this.createDOM();
				this.set('_chart', new RGraph[this.get('type')](this.get('id'), this.get('data')));
				var properties = this.get('properties');
				for (key in properties) {
					this.get('_chart').Set(key, properties[key]);
				}
			},
			createDOM: function() {
				$('<div />')
					.attr('id', this.get('id')+'-container')
					.append($('<h4 />').html(this.get('title')))
					.append($('<canvas />')
					.attr('id', this.get('id'))
					.attr('width', this.get('width'))
					.attr('height', this.get('height'))
				).appendTo(this.get('placeholder'));
			},
			setData: function(data) {
				this.set('data', data);
			},
			render: function() {
				this.initChart();
				this.get('_chart').Draw();
			}
		});
	}
);