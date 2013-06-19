============================================================
This are the setup commands to create a working mister maks.
============================================================

Start
-----
what you have allready have done::

	get a clone of the repository and setup everything by using composer.
	And point the virtual host to the right location
	PHP 5.4 or higher is installed
	CouchDB and Mysql or postgress is installed
	You have created a database in mysql or postgress

Patch TYPO3 party
-----------------

The TYPO3 Party package should be patched according to this instruction.

.. toctree::
	:maxdepth: 2
	:numbered:

	patch


configuration database
----------------------

setup database settings in config folder

*Development*::

	/ehrm-base/Configuration/Development/Settings.yaml

*Production*::

	/ehrm-base/configuration/Production/Settings.yaml

after that run the following commands on cli

*to setup mysql or postgress database*::

	./flow doctrine:migrate

*to setup couchDB*::

	./flow migrate:designs

*to create initial company*::

	./flow setup:initialize <CompanyName>

*to create initial user::

	./flow ehrm:user:create [<options>] <username> <password> <first name> <last name> <roles>

*import resource files*

contractarticles::

	./flow import:collection Beech.CLA ContractArticle Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/ContractArticle/ContractArticles.yaml contractArticles.articles --language nl

jobdescriptions::

	 ./flow import:yaml Beech.CLA JobDescription --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/JobDescription/

ContractTemplates::

	./flow import:yaml Beech.CLA ContractTemplate --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/ContractTemplates/ --pathInYaml contractTemplate