<?php
namespace Beech\Importer\Utility;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;
use Symfony\Component\Yaml\Yaml;

/**
 * Class which support importing yaml files
 * as a objects in CouchDb
 *
 */
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
	protected $collectionIndex = '';

	/**
	 * Number of collection elements
	 * @var int
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
	 * @return int
	 */
	public function getNumberOfImportedElements() {
		return $this->numberOfImportedElements;
	}

	/**
	 * If yaml file contains collection of object, collectionIndex
	 * is used to localize a position
	 *
	 * Example: Index contractArticles:articles
	 * -> array('contractArticles'
	 * 		=> array('articles'
	 * 			=> array(...))
	 *
	 * @param $collectionIndex
	 */
	public function setCollectionIndex($collectionIndex) {
		if (!empty($collectionIndex)) {
			$this->collectionIndex = $collectionIndex;
			$this->isCollection = TRUE;
		} else {
			$this->isCollection = FALSE;
		}
	}

	/**
	 * Get value from yaml file located at collectionIndex
	 *
	 * @param array $parsedYaml
	 * @return mixed
	 */
	private function getByCollectionIndex(array $parsedYaml) {
		$indexes = explode(':', $this->collectionIndex);
		foreach ($indexes as $index) {
			$parsedYaml = $parsedYaml[$index];
		}
		return $parsedYaml;
	}

	/**
	 * Store data to database through prepared document.
	 *
	 * @param array $parsedYaml
	 * @return void
	 */
	protected function store(array $parsedYaml) {
		if ($this->isCollection) {
			$collection = $this->getByCollectionIndex($parsedYaml);
			foreach ($collection as $collectionElement) {
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
				if ($value['type'] === 'dateTime' && $value['default'] === 'now') {
					$defaultValue = date('Y-m-d');
				} elseif ($value['default'] === 'currentUser') {
						// TODO: Get default user or other solution, maybe from config file?
					$defaultValue = 'MisterMaks';
				} else {
					$defaultValue = $value['default'];
				}
				ObjectAccess::setProperty($document, $key, $defaultValue);
			}
		}
		foreach ($parsedYaml as $key => $value) {
				// check if value is language array
			if (isset($value[$this->language])) {
				$value = $value[$this->language];
			}
			ObjectAccess::setProperty($document, $key, $value);
		}

		return $document;
	}

}
?>