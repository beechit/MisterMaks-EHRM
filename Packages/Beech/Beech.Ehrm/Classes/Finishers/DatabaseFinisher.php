<?php
namespace Beech\Ehrm\Finishers;

/*                                                                        *
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

		$packageKey = $this->parseOption('package');
		$modelName = $this->parseOption('model');

		if ($packageKey === NULL) {
			throw new \TYPO3\Form\Exception\FinisherException('The option "Package Key" must be set for the DatabaseFinisher.');
		}

		if ($modelName === NULL) {
			throw new \TYPO3\Form\Exception\FinisherException('The option "Model Name" must be set for the DatabaseFinisher.');
		}

			// Resolve name and instantiate model and repository
		$strModel = '\\' . $packageKey . '\Domain\Model\\' . ucfirst($modelName);
		$strRepository = '\\' . $packageKey . '\Domain\Repository\\' . ucfirst($modelName) . 'Repository';

		$model = new $strModel;
		$repository = new $strRepository;

			// Map forminput to the model and store data
		foreach ($this->finisherContext->getFormValues() AS $key => $val) {
			$methodName = 'set' . ucfirst($key);
			$model->$methodName($val);
		}
		$repository->add($model);
	}
}

?>