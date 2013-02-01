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
	protected $yamlImport;

	/**
	 * Command to import yaml files to couchDb
	 *
	 * Examle usage:
	 *
	 * ./flow import:yaml Packages/Application/MyCompany.MyPackage/Resources/Private/YamlFiles/ MyCompany.OtherPackage ClassOfModel
	 *
	 * @param string $source Path to directory which contain yaml files
	 * @param string $packageKey The package key, for example "MyCompany.MyPackageName"
	 * @param string $modelName Class name of created object
	 * @return void
	 */
	public function yamlCommand($source, $packageKey, $modelName) {
		$this->yamlImport->init($packageKey, $modelName);
		$this->yamlImport->import($source);
		$this->outputLine('Import complete. %d objects were imported.', array($this->yamlImport->getNumberOfFiles()));
	}

}
?>