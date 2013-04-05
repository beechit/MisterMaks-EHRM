==============
Importcommands
==============

These are the importcommands cli for importing the following entities:

	* Jobdescriptions
	* ContractTemplates
	* ContractArticles

contractarticles::

	./flow import:collection Beech.CLA ContractArticle Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/ContractArticle/contractArticles.yaml contractArticles.articles --language nl

jobdescriptions::

	 ./flow import:yaml Beech.CLA JobDescription --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/JobDescription/

ContractTemplates::

	./flow import:yaml Beech.CLA ContractTemplate --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/Contract/ --pathInYaml contractTemplate
