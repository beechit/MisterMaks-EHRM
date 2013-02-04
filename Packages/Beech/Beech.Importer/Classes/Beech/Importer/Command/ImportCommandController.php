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
	 * Examle usage:
	 *
	 * ./flow import:yaml MyCompany.OtherPackage ClassOfModel Packages/Application/MyCompany.MyPackage/Resources/Private/YamlFiles/
	 *
	 * @param string $sourcePath Path to directory which contain YAML files
	 * @param string $packageKey The package key, for example "MyCompany.MyPackageName"
	 * @param string $modelName Class name of created object
	 * @return void
	 */
	public function yamlCommand($packageKey, $modelName, $sourcePath = NULL) {
		if ($sourcePath === NULL) {
			$sourcePath = 'resource://' . $packageKey . '/Private/Yaml/';
		}
		$this->yamlImportUtility->init($packageKey, $modelName);
		$this->yamlImportUtility->import($sourcePath);
		$this->outputLine('Import complete. %d objects were imported.', array($this->yamlImportUtility->getNumberOfImportedFiles()));
	}

}
?>