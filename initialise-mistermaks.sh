# This file is used to auto update the test and staging environments.
./flow doctrine:migrate
./flow migrate:designs
./flow setup:initialize Beech.it
./flow role:import
./flow ehrm:user:create admin admin admin admin Beech.Ehrm:Administrator
# the imports should be done once otherwise they break current data in couchdb.
#./flow import:collection Beech.CLA ContractArticle Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/ContractArticle/ContractArticles.yaml contractArticles.articles --language nl
./flow import:yaml Beech.CLA JobDescription --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/JobDescription/
#./flow import:yaml Beech.CLA ContractTemplate --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/ContractTemplates/ --pathInYaml contractTemplate
#./flow import:collection Beech.Document DocumentType resource://Beech.Document/Private/Data/DocumentType documentTypes
./flow import:yaml Beech.Absence AbsenceArrangement --sourcePath Packages/Beech/Beech.Absence/Resources/Private/Data/AbsenceArrangements/ --pathInYaml absenceArrangement
