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

to set up a "production enviroment"

use php composer.phar install

to setup a dev enviroment (due to use of satis for package distribution you have to use

composer install --dev --prefer-source

otherwise the .git folders dont exist
they are needed for the php gerrit update part.




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

Contract articles::

	./flow import:collection Beech.CLA ContractArticle Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/ContractArticle/ContractArticles.yaml contractArticles.articles --language nl

Job descriptions::

	./flow import:yaml Beech.CLA JobDescription --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/JobDescription/

Contract templates::

	./flow import:yaml Beech.CLA ContractTemplate --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/ContractTemplates/ --pathInYaml contractTemplate

Document types::

	./flow import:collection Beech.Document DocumentType resource://Beech.Document/Private/Data/DocumentType documentTypes

Absence arrangements::
	./flow import:yaml Beech.Absence AbsenceArrangement --sourcePath Packages/Beech/Beech.Absence/Resources/Private/Data/AbsenceArrangements/ --pathInYaml absenceArrangement



Production context
-----------------

if not configured differently by the server environment, (SETenv: Production in vhost or change context in .htaccess) the flow script is run in the Development context by default. It is recommended to set the FLOW_CONTEXT environment variable to Production on a production server – that way you don't execute commands in an unintended context accidentally.
If you usually run the flow script in one context but need to call it in another context occasionally, you can do so by temporarily setting the respective environment variable for the single command run::

	FLOW_CONTEXT=Production ./flow flow:cache:flush

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
./flow import:yaml Beech.Absence AbsenceArrangement --sourcePath Packages/Beech/Beech.Absence/Resources/Private/Data/AbsenceArrangements/ --pathInYaml absenceArrangement
./flow import:yaml Beech.CLA SalaryScale --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/Wage/ --pathInYaml SalaryScale
