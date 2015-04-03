======
RGraph
======

General info about plugin
=========================

RGraph is HTML5 charts library written in javascript that uses the HTML5 canvas tag to draw
and supports over twenty different types of charts.

Init
==============
Initialization of RGraph plugin is realized including 'Beech.Ehrm/JavaScript/RGraph' script.
This is implementation of RGraph, written with usage of emberJs.

Types of graph
==============
RGraph supports 22 types of charts and many can be made to look significantly different through the use of the properties.

More info:
`<http://www.rgraph.net/docs/charts-index.html>`_


Usage
=====
To create graph, two functions has to be executed::

 RGraph.initialize(options);
 RGraph.render();

Options
=======
Options are object with JSON format, which contains full details about graph to render.

Required options
----------------
Some options are required to proper render chart

``id`` -     identifier of chart inside DOM document

``type`` -     type of chart to create, for example, Bar, Pie, Gantt etc

``data`` -     data used to render chart

Optional options
----------------
Optional options to render chart

``placeholder`` -     place on page inside which chart will be displayed. defualt is ``'body'``

``title`` -     title above chart

``width`` -     width of chart

``height`` -     height of chart

``properties`` -     properties specified for chart, depends on type. More info in documentation of RGraph `<http://www.rgraph.net/docs/charts-index.html>`_

Example
-------
This is example options for generating simple::

 var options = {
	'id': 'bar1', // [required]
	'placeholder': 'body', // [optional]
	'title': 'Bar chart', // [optional]
	'data': [12,13,16,15,16,19,19,12,53,16,13,24], //[required]
	'type': 'Bar', //[required]
	'properties': {
		'chart.labels': ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
		'chart.gutter.left': 35,
		'chart.title': 'A basic chart',
		'chart.shadow': true
	}
 }

