<?php
namespace Beech\Importer\Utility;

use TYPO3\Flow\Annotations as Flow;
use Symfony\Component\Yaml\Yaml;

/**
 * Class which support importing yaml files
 * as a objects in CouchDb
 *
 */
class YamlImportUtility {

	/**
	 * Full path and class name of imported model
	 * @var string
	 */
	protected $modelClassName;

	/**
	 * Instance of repository
	 * @var
	 */
	protected $repository;

	/**
	 * List of files to import
	 * @var array
	 */
	protected $filesToImport = array();

	/**
	 * Initialize importer.
	 * Setting up model name and repository instance
	 *
	 * @param $packageKey
	 * @param $model
	 */
	public function init($packageKey, $modelName) {
		$repositoryClassName = '\\' . str_replace('.', '\\', $packageKey) . '\Domain\Repository\\' . $modelName . 'Repository';
		$this->repository = new $repositoryClassName();
		$this->modelClassName = '\\' . str_replace('.', '\\', $packageKey) . '\Domain\Model\\' . $modelName;
	}

	/**
	 * Remove all documents from database
	 */
	public function clean() {
		$this->repository->removeAll();
	}

	/**
	 * Check if document exist in database
	 * Its possible to specify different propertyName used to compare
	 *
	 * @param $document
	 * @param $propertyName
	 * @return bool
	 */
	public function exist($document, $propertyName = 'id') {
		$getMethod = 'get' . ucfirst($propertyName);
		$findMethod = 'findBy' . ucfirst($propertyName);
		return (count($this->repository->$findMethod($document->$getMethod())) > 0) ? TRUE : FALSE;
	}

	/**
	 * Set values for properties of document
	 *
	 * @param $yamlContent
	 * @return mixed Prepared document
	 */
	public function prepareDocument($yamlContent) {
		$document = new $this->modelClassName();
		foreach ($yamlContent as $fieldName => $fieldValue) {
			$methodName = 'set' . ucfirst($fieldName);
			$document->$methodName($fieldValue);
		}
		return $document;
	}

	/**
	 * Store prepared document to database
	 * @param $yamlContent
	 */
	public function store($yamlContent) {
		$document = $this->prepareDocument($yamlContent);
		$this->repository->add($document);
	}

	/**
	 * Return number of yaml files to import
	 * @return int
	 */
	public function getNumberOfFiles() {
		return count($this->filesToImport);
	}

	/**
	 * Get array with list of files
	 * @return array
	 */
	public function getFiles() {
		return $this->filesToImport;
	}

	/**
	 * Read yaml files for specified directory
	 * @param $dir
	 */
	public function readFiles($dir) {
		$this->filesToImport = \TYPO3\Flow\Utility\Files::readDirectoryRecursively($dir, 'yaml');
	}

	/**
	 * Process import
	 * @param $source
	 */
	public function import($source) {
		$this->clean();
			// get list of files
		$this->readFiles($source);
		foreach ($this->getFiles() as $path) {
			$this->store(Yaml::parse($path));
		}
	}
}
?>