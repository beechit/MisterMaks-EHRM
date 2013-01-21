======================================
Infovis spacetree plugin customization
======================================

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
	Method to set up container inside html dom. Param 'id' is a id html element.
	Example:
	TreeDiagram.setContainer('social-infovis');

* set(key, value)
	This method allow to overwrite content of variables used in diagram.
	List of variables: container, orientation, constrained, levelsToShow, injectInto, duration, levelDistance, navigation, node, edge
	More info http://philogb.github.com/jit/static/v20/Docs/files/Visualizations/Spacetree-js.html

* initialize()
	Method used to initialize diagram. All settings should be set up before this method.

* load(url)
	Method used to load data from specified url. Data should be in json format
	Example of data format: Resources/Public/Data/diagram.json


