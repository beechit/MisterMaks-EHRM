============================================================
This are the setup commands to create a working mister maks.
============================================================

Start
-----
what you have allready done::

	get a clone of the repository and setup everything by using composer.
	And point the virtual host to the right location
	PHP 5.4 or higher is installed
	CouchDB and Mysql or postgress is installed



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

to setup couchDB::

	./flow migrate:designs

to create initial company

	./flow setup:initialize <CompanyName>





