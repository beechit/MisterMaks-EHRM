./flow doctrine:migrate
./flow migrate:designs
./flow setup:initialize Beech.it
./flow role:import
./flow ehrm:user:create admin admin admin admin Beech.Ehrm:Administrator
./flow import:collection Beech.CLA ContractArticle Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/ContractArticle/ContractArticles.yaml contractArticles.articles --language nl
./flow import:yaml Beech.CLA JobDescription --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/JobDescription/
./flow import:yaml Beech.CLA ContractTemplate --sourcePath Packages/Beech/Beech.Ehrm.Glastuinbouw/Resources/Private/Data/ContractTemplates/ --pathInYaml contractTemplate
./flow import:collection Beech.Document DocumentType resource://Beech.Document/Private/Data/DocumentType documentTypes