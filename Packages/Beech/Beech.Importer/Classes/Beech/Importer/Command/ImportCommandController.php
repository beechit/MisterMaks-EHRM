<?php
namespace Beech\Importer\Command;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 19-09-12 11:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Symfony\Component\Yaml\Yaml;

/**
 * Command line controller for import yaml files
 *
 * @Flow\Scope("singleton")
 */
class ImportCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \Beech\Importer\Utility\YamlImportUtility
	 * @Flow\Inject
	 */
	protected $yamlImportUtility;

	/**
	 * Command to import YAML files to CouchDb
	 *
	 * Example usage:
	 *
	 * ./flow import:yaml MyCompany.OtherPackage ClassOfModel --sourcePath Packages/Application/MyCompany.MyPackage/Resources/Private/YamlFiles/
	 *
	 * @param string $packageKey The package key, for example "MyCompany.MyPackageName"
	 * @param string $modelName Class name of created object
	 * @param string $sourcePath Path to directory which contain YAML files
	 * @param string $configurationPath Path to custom configuration
	 * @return void
	 */
	public function yamlCommand($packageKey, $modelName, $sourcePath = NULL, $configurationPath = NULL) {
		if ($sourcePath === NULL) {
			$sourcePath = 'resource://' . $packageKey . '/Private/Yaml/';
		}
		$this->yamlImportUtility->init($packageKey, $modelName, $configurationPath);
		$this->yamlImportUtility->import($sourcePath);
		$this->outputLine('Import complete. %d objects were imported.', array($this->yamlImportUtility->getNumberOfImportedFiles()));
	}

	/**
	 * Command to import YAML file with collection of objects
	 *
	 * ./flow import:collection MyCompany.OtherPackage ClassOfModel
	 * 	--sourcePath Packages/Application/MyCompany.MyPackage/Resources/Private/YamlFiles/
	 * 	--collectionIndex contactArticles:articles --language nl
	 *
	 * @param string $packageKey The package key, for example "MyCompany.MyPackageName"
	 * @param string $modelName
	 * @param string $sourcePath Path to YAML file
	 * @param string $collectionPath Path to value of a nested array, for example "contractArticles.articles"
	 * @param string $language
	 */
	public function collectionCommand($packageKey, $modelName, $sourcePath, $collectionPath, $language = 'en') {
		$this->yamlImportUtility->init($packageKey, $modelName);
		$this->yamlImportUtility->setCollectionPath($collectionPath);
		$this->yamlImportUtility->setLanguage($language);
		$this->yamlImportUtility->import($sourcePath);
		$this->outputLine('Import complete. %d objects were imported.', array($this->yamlImportUtility->getNumberOfImportedElements()));
	}
}
?>