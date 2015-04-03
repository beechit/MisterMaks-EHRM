=========================================
How to create new package for Mister Maks
=========================================

Create package directory
------------------------
Create directory /Packages/Beech/Beech.NewPackage/

Add to composer
---------------
To make it visible for Mister Maks, composer file should be created in:
/Packages/Beech/Beech.NewPackage/composer.json

with content:
{
	"name": "beech/newpackage",
	"type": "typo3-flow-package",
	"description": "",
	"version": "",
	"require": {
		"typo3/flow": "*",
		"beech/ehrm": "*"
	},
	"autoload": {
		"psr-0": {
			"Beech\\NewPackage": "Classes"
		}
	}
}

Model, View and Controller
--------------------------
Create controller:
- /Packages/Beech/Beech.NewPackage/Classes/Beech/NewPackage/Controller/ModelNameController.php

Model:
- /Packages/Beech/Beech.NewPackage/Classes/Beech/NewPackage/ModelName.php

Template:
- /Packages/Beech/Beech.NewPackage/Resources/Private/Templates/ModelName/Index.html

Create routes
-------------
Setup routes for javascript part:
/Packages/Beech/Beech.Ehrm/Resources/Public/JavaScript/Router.js

Create ember files:
/Packages/Beech/Beech.NewPackage/Resources/Public/JavaScript/Controller/ModelName.js
/Packages/Beech/Beech.NewPackage/Resources/Public/JavaScript/Route/ModelName.js
/Packages/Beech/Beech.NewPackage/Resources/Public/JavaScript/View/ModelName.js

and add them to '/Packages/Beech/Beech.Ehrm/Configuration/Settings.yaml' as
Beech.Ehrm.resources.javaScript.sources
- 'Beech.NewPackage/JavaScript/Route/ModelName.js'
- 'Beech.NewPackage/JavaScript/Controller/ModelName.js'
- 'Beech.NewPackage/JavaScript/View/ModelName.js'

Add entry to 'Beech.Ehrm.moduleRoutes' to auto-generate connection between ember part and
php models.
- ['Beech.NewPackage', NULL, 'ModelName']

Create Views.yaml
-----------------
Mister Maks use
To overwrite default FLOW behaviour and use the same layout for each package,
a file on location '/Packages/Beech/Beech.NewPackage/Configuration/Views.yaml' should be created

'Beech.NewPackage':
  requestFilter: 'isPackage("Beech.NewPackage")'
  viewClassName: 'Beech\Ehrm\View\TemplateView'
  options:
    layoutRootPaths:
      - 'resource://Beech.Ehrm/Private/Layouts'




