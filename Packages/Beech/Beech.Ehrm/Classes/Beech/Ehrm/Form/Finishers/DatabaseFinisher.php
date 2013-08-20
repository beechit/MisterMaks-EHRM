<?php
namespace Beech\Ehrm\Form\Finishers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-06-12 10:47
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;

/**
 * This finisher stores a model using user-generated formdata
 * Options:
 * - package (mandatory): Name of the package in which the model can be found (i.e.: Beech\Party)
 * - model (mandatory): The model receiving and storing data (i.e.: Company)
 */
class DatabaseFinisher extends \TYPO3\Form\Core\Model\AbstractFinisher {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * @var array
	 */
	protected $defaultOptions = array(
		'package' => '',
		'model' => '',
	);

	/**
	 * Executes this finisher
	 *
	 * @see AbstractFinisher::execute()
	 * @return void
	 * @throws \TYPO3\Form\Exception\FinisherException
	 */
	protected function executeInternal() {
		$packageNamespace = str_replace('.', '\\', $this->parseOption('package'));
		$modelName = $this->parseOption('model');

		if ($packageNamespace === NULL) {
			throw new \TYPO3\Form\Exception\FinisherException('The option "package" must be set for the DatabaseFinisher.');
		}

		if ($modelName === NULL) {
			throw new \TYPO3\Form\Exception\FinisherException('The option "modelName" must be set for the DatabaseFinisher.');
		}

			// Resolve name and instantiate model and repository
		$modelClassName = '\\' . $packageNamespace . '\Domain\Model\\' . ucfirst($modelName);
		$repositoryClassName = '\\' . $packageNamespace . '\Domain\Repository\\' . ucfirst($modelName) . 'Repository';

		$model = new $modelClassName();
		$repository = new $repositoryClassName();

			// Map form input to the model and store data
		foreach ($this->finisherContext->getFormValues() as $key => $value) {
			if (is_array($value)) {
				$subModelClassName = '\\' . $packageNamespace . '\\Domain\\Model\\' . ucfirst($key);
				if (!class_exists($subModelClassName)) {
					$subModelClassName = '\\TYPO3\\Party\\Domain\\Model\\' . ucfirst($key);
				}
				$subModel = new $subModelClassName();

				foreach ($value as $property => $propertyValue) {
					$methodName = 'set' . ucfirst($property);
					$subModel->$methodName($propertyValue);
				}
				$methodName = 'add' . ucfirst($key);
				$model->$methodName($subModel);
			} else {
				$methodName = 'set' . ucfirst($key);
				$model->$methodName($value);
			}
		}
		$repository->add($model);
	}
}

?>