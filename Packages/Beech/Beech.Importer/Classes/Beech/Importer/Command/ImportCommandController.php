<?php
namespace Beech\Importer\Command;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */
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
	 * --path path.in.yaml.seperated.by.dot
	 *
	 * @param string $packageKey The package key, for example "MyCompany.MyPackageName"
	 * @param string $modelName Class name of created object
	 * @param string $sourcePath Path to directory which contain YAML files
	 * @param string $configurationPath Path to custom configuration. If yaml model is on different location
	 * @param string $pathInYaml Path to value of a nested array, for example "contractArticles.articles"
	 * @return void
	 */
	public function yamlCommand($packageKey, $modelName, $sourcePath = NULL, $configurationPath = NULL, $pathInYaml = '') {
		if ($sourcePath === NULL) {
			$sourcePath = 'resource://' . $packageKey . '/Private/Yaml/';
		}
		$this->yamlImportUtility->init($packageKey, $modelName, $configurationPath);
		if (!empty($pathInYaml)) {
			$this->yamlImportUtility->setPath($pathInYaml);
		}
		$this->yamlImportUtility->import($sourcePath);
		$this->outputLine('Import complete. %d objects were imported.', array($this->yamlImportUtility->getNumberOfImportedFiles()));
	}

	/**
	 * Command to import YAML file with collection of objects
	 *
	 * ./flow import:collection MyCompany.OtherPackage ClassOfModel
	 * 	--sourcePath Packages/Application/MyCompany.MyPackage/Resources/Private/YamlFiles/
	 * 	--pathInYaml contactArticles.articles --language nl
	 *
	 * @param string $packageKey The package key, for example "MyCompany.MyPackageName"
	 * @param string $modelName
	 * @param string $sourcePath Path to YAML file
	 * @param string $pathInYaml Path to value of a nested array, for example "contractArticles.articles"
	 * @param string $language
	 */
	public function collectionCommand($packageKey, $modelName, $sourcePath, $pathInYaml, $language = 'en') {
		$this->yamlImportUtility->init($packageKey, $modelName);
		$this->yamlImportUtility->setCollection(TRUE);
		$this->yamlImportUtility->setPath($pathInYaml);
		$this->yamlImportUtility->setLanguage($language);
		$this->yamlImportUtility->import($sourcePath);
		$this->outputLine('Import complete. %d objects were imported.', array($this->yamlImportUtility->getNumberOfImportedElements()));
	}
}
?>