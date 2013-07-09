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

for this to work a database name must be set in settings.yaml

*to create initial company*::

	./flow setup:initialize <CompanyName>

*to initialize system roles, based on Policy.yaml files

	./flow role:import

*to create initial user::

	./flow ehrm:user:create [<options>] <username> <password> <first name> <last name> <roles>

example::

	./flow ehrm:user:create edward beech Edward Lenssen Beech.Ehrm:Administrator




*import resource files*

contractarticles::

	./flow import:collection Beech.CLA ContractArticle Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/ContractArticle/ContractArticles.yaml contractArticles.articles --language nl

jobdescriptions::

	 ./flow import:yaml Beech.CLA JobDescription --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/JobDescription/

ContractTemplates::

	./flow import:yaml Beech.CLA ContractTemplate --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/ContractTemplates/ --pathInYaml contractTemplate

DocumentType::

	./flow import:collection Beech.Document DocumentType resource://Beech.Document/Private/Data/DocumentType documentTypes


for easy cut and paste work.
----------------------------

./flow doctrine:migrate
./flow migrate:designs


./flow setup:initialize Beech.it
./flow role:import
./flow ehrm:user:create admin admin admin admin Beech.Ehrm:Administrator
./flow import:collection Beech.CLA ContractArticle Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/ContractArticle/ContractArticles.yaml contractArticles.articles --language nl
./flow import:yaml Beech.CLA JobDescription --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/JobDescription/
./flow import:yaml Beech.CLA ContractTemplate --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/ContractTemplates/ --pathInYaml contractTemplate
./flow import:collection Beech.Document DocumentType resource://Beech.Document/Private/Data/DocumentType documentTypes