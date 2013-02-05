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
	 * Full path and class name of imported model
	 * @var string
	 */
	protected $modelClassName;

	/**
	 * Instance of repository
	 * @var \TYPO3\Flow\Persistence\RepositoryInterface
	 */
	protected $repository;

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
	 * @return void
	 */
	public function init($packageKey, $modelName) {
		$modelName = ucfirst($modelName);
		$repositoryClassName = str_replace('.', '\\', $packageKey) . '\Domain\Repository\\' . $modelName . 'Repository';
		$this->modelClassName = str_replace('.', '\\', $packageKey) . '\Domain\Model\\' . $modelName;

		$this->repository = $this->objectManager->get($repositoryClassName);
	}

	/**
	 * Process import
	 *
	 * @param string $sourcePath
	 * @return void
	 */
	public function import($sourcePath) {
		$this->importedFiles = array();
		$this->filesToImport = \TYPO3\Flow\Utility\Files::readDirectoryRecursively($sourcePath, 'yaml');

		if (count($this->filesToImport) > 0) {
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
	 * Store data to database through prepared document.
	 *
	 * @param array $parsedYaml
	 * @return void
	 */
	protected function store(array $parsedYaml) {
		$document = $this->prepareDocument($parsedYaml);
		$this->repository->add($document);
	}

	/**
	 * Set values for properties of document
	 *
	 * @param array $parsedYaml
	 * @return mixed Prepared document
	 */
	protected function prepareDocument(array $parsedYaml) {
		$document = new $this->modelClassName();
		foreach ($parsedYaml as $key => $value) {
			ObjectAccess::setProperty($document, $key, $value);
		}
		return $document;
	}

}
?>