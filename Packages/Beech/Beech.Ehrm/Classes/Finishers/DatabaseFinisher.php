<?php
namespace Beech\Ehrm\Finishers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-06-12 10:47
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * This finisher stores a model using user-generated formdata
 *
 * Options:
 *
 * - package (mandatory): Name of the package in which the model can be found (i.e.: Beech\Party)
 * - model (mandatory): The model receiving and storing data (i.e.: Company)
 */
class DatabaseFinisher extends \TYPO3\Form\Core\Model\AbstractFinisher {

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
		$strModel = '\\' . $packageNamespace . '\Domain\Model\\' . ucfirst($modelName);
		$strRepository = '\\' . $packageNamespace . '\Domain\Repository\\' . ucfirst($modelName) . 'Repository';

		$model = new $strModel;
		$repository = new $strRepository;

			// Map form input to the model and store data
		foreach ($this->finisherContext->getFormValues() AS $key => $val) {
			$methodName = 'set' . ucfirst($key);
			$model->$methodName($val);
		}
		$repository->add($model);
	}
}

?>