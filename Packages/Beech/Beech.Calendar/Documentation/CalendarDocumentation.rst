=================================
FullCalendar plugin documentation
=================================

Required files:
---------------
From Beech.Ehrm package:
* Library/jit/css/base.css (general css used by several plugins)
* Library/jit/css/spacetree.css (css used by the spacetree)
* Javascript/TreeDiagram.js (the spacetree javascript used to configure the diagram and how it responds)

Example of usage:
-----------------
Code with example is in Init.js file on location Resources/Public/JavaScript/Init.js

Methods
-------
* setContainer(id)
	Method to set up container inside html dom. Param 'id' is a id html element. Default id is 'calendar'.
	Example:
	Calendar.setContainer('calendar');

* setSize(width, height)
	Method to set up size of container.
	Param 'width' is a width of html element.
	Param 'height' is a height of html element.
	Default is full size.
	Example:
	Calendar.setContainer('calendar');

* center()
	Method to set up calendar in the center of page.
	Example:
	Calendar.center();

* set(key, value)
	This method allow to overwrite content of variables used in calendar.
	List of available variables: defaultView, weekends
	More info in documentation: http://arshaw.com/fullcalendar/docs/

* initialize()
	Method used to initialize calendar. All settings should be set up before this method.

* addEventSource(url, color, textColor)
	Method used to add source of data from specified url. Data should be in json format
	Example of data format: Resources/Public/Data/exampleDates.json
	Params:
	url - url of data source in json format
	color - color for background of event
	textColor - text color for event


More documentation
------------------
http://arshaw.com/fullcalendar/docs/

TODO
----
* implement internationalization
