<?php
namespace Beech\Importer\Utility;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;
use Symfony\Component\Yaml\Yaml;

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

class YamlImportUtility {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var \Beech\Ehrm\Utility\Domain\ModelInterpreterUtility
	 * @Flow\Inject
	 */
	protected $modelInterpreterUtility;

	/**
	 * Full path and class name of imported model
	 * @var string
	 */
	protected $modelClassName;

	/**
	 * Structure of model stored in YAML file
	 * @var array
	 */
	protected $yamlModel;

	/**
	 * Instance of repository
	 * @var \TYPO3\Flow\Persistence\RepositoryInterface
	 */
	protected $repository;

	/**
	 * Language used as a default
	 * @var string
	 */
	protected $language = 'en';

	/**
	 * @var boolean
	 */
	protected $isCollection = FALSE;

	/**
	 * @var string
	 */
	protected $path = '';

	/**
	 * Number of collection elements
	 * @var integer
	 */
	protected $numberOfImportedElements = 0;

	/**
	 * List of files to import
	 * @var array
	 */
	protected $filesToImport = array();

	/**
	 * Files actually imported
	 * @var array
	 */
	protected $importedFiles = array();

	/**
	 * Initialize importer.
	 * Setting up model name and repository instance
	 *
	 * @param string $packageKey
	 * @param string $modelName
	 * @param string $yamlModel
	 * @return void
	 */
	public function init($packageKey, $modelName, $yamlModel = NULL) {
		$modelName = ucfirst($modelName);
		$repositoryClassName = str_replace('.', '\\', $packageKey) . '\\Domain\\Repository\\' . $modelName . 'Repository';
		$this->modelClassName = str_replace('.', '\\', $packageKey) . '\\Domain\\Model\\' . $modelName;
		if ($yamlModel === NULL) {
			$yamlModel = $this->modelInterpreterUtility->getModelProperties($packageKey, $modelName);
		}
		$this->yamlModel = $this->modelInterpreterUtility->getModelProperties($packageKey, $modelName, $yamlModel);
		$this->repository = $this->objectManager->get($repositoryClassName);
	}

	/**
	 * Set default language
	 *
	 * @param $language
	 */
	public function setLanguage($language) {
		$this->language = $language;
	}

	/**
	 * Process import
	 *
	 * @param string $sourcePath
	 * @throws \Exception
	 * @return void
	 */
	public function import($sourcePath) {
		$this->importedFiles = array();
		if (is_dir($sourcePath)) {
			$this->filesToImport = \TYPO3\Flow\Utility\Files::readDirectoryRecursively($sourcePath, 'yaml');
		} else {
			if (!file_exists($sourcePath)) {
				throw new \Exception(sprintf('Unable to parse "%s" as the file is not exists.', $sourcePath));
			}
			$this->filesToImport[] = $sourcePath;
		}
		if ($this->filesToImport !== array()) {
			$this->repository->removeAll();

			foreach ($this->filesToImport as $pathAndFilename) {
				$this->store(Yaml::parse($pathAndFilename));
				$this->importedFiles[] = $pathAndFilename;
			}
		}
	}

	/**
	 * Return number of YAML files imported.
	 *
	 * @return integer
	 */
	public function getNumberOfImportedFiles() {
		return count($this->importedFiles);
	}

	/**
	 * Return number of elements of collection, imported as objects
	 *
	 * @return integer
	 */
	public function getNumberOfImportedElements() {
		return $this->numberOfImportedElements;
	}

	/**
	 * Set if yaml file contains collection of object,
	 * If it is set up to TRUE, that means that there is collection of objects in yaml file
	 * and each of element indicated by path contains object to import
	 *
	 * @param boolean $isCollection
	 */
	public function setCollection($isCollection) {
		$this->isCollection = $isCollection;
	}

	/**
	 * Set path inside yaml
	 *
	 * Example: path equals contractArticles.articles indicates
	 * -> array('contractArticles'
	 * 		=> array('articles'
	 * 			=> array(...))
	 *
	 * @param string $path
	 */
	public function setPath($path) {
		$this->path = $path;
	}

	/**
	 * Store data to database through prepared document.
	 *
	 * @param array $parsedYaml
	 * @return void
	 */
	protected function store(array $parsedYaml) {
		if (!empty($this->path)) {
			$parsedYaml = \TYPO3\Flow\Utility\Arrays::getValueByPath($parsedYaml, $this->path);
		}
		if ($this->isCollection) {
			foreach ($parsedYaml as $collectionElement) {
				$this->numberOfImportedElements++;
				$document = $this->prepareDocument($collectionElement);
				$this->repository->add($document);
			}
		} else {
			$document = $this->prepareDocument($parsedYaml);
			$this->repository->add($document);
		}
	}

	/**
	 * Set values for properties of document
	 *
	 * @param array $parsedYaml
	 * @return mixed Prepared document
	 */
	protected function prepareDocument(array $parsedYaml) {
		$document = new $this->modelClassName();
		foreach ($this->yamlModel as $key => $value) {
			if (isset($value['default'])) {
				if (isset($value['type']) && $value['type'] === 'DateTime' && $value['default'] === 'now') {
					$defaultValue = new \DateTime();
				} elseif ($value['default'] === 'currentUser') {
						// TODO: Get default user or other solution, maybe from config file?
					$defaultValue = NULL;
				} else {
					$defaultValue = $value['default'];
				}
				ObjectAccess::setProperty($document, $key, $defaultValue);
			}
		}
		foreach ($parsedYaml as $key => $value) {
				// check if value is language array
			try {
				ObjectAccess::setProperty($document, $key, $value);
			} catch (\Exception $exception) {
				echo PHP_EOL.get_class($document).'->'.$key." ".$exception->getMessage().PHP_EOL.PHP_EOL;
			}
		}

		return $document;
	}

}
?>