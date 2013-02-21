<?php
namespace Beech\Ehrm\Finishers;

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
			// TODO: create ContractFinisher or do DatabaseFinisher more generic
		if ($modelName === 'Contract') {
			$this->storeContract($model, $repository);
		} else {
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

	/**
	 * @param \Beech\CLA\Domain\Model\Contract $contract
	 * @param \Beech\CLA\Domain\Repository\ContractRepository $repository
	 * @return void
	 */
	protected function storeContract(\Beech\CLA\Domain\Model\Contract $contract, \Beech\CLA\Domain\Repository\ContractRepository $repository) {
		$formValues = $this->finisherContext->getFormValues();
			// TODO: Maybe do this more generic
		if (isset($formValues['employee'])) {
			$employeeRepository = new \Beech\Party\Domain\Repository\PersonRepository();
			$employee = $employeeRepository->findByIdentifier($formValues['employee']);
			$contract->setEmployee($employee);
			$contract->setEmployeeFullName($employee->getName()->getFullName());
		}
		if (isset($formValues['jobDescription'])) {
			$jobDescriptionRepository = new \Beech\CLA\Domain\Repository\JobDescriptionRepository();
			$jobDescription = $jobDescriptionRepository->findByIdentifier($formValues['jobDescription']);
			$contract->setJobDescription($jobDescription);
			$contract->setJobDescriptionName($jobDescription->getJobTitle());
		}
		$contract->setStatus(\Beech\CLA\Domain\Model\Contract::STATUS_DRAFT);

		if (isset($formValues['contractTemplate'])) {
			$contract->setContractTemplate($formValues['contractTemplate']);
		}
			// TODO: Store company identifier (employer)
		$articles = array();

		foreach ($this->finisherContext->getFormValues() as $key => $values) {
			if (strpos($key, 'article') === 0) {
				if (preg_match('/article-(\d+)-values/', $key, $articleId)) {
					$articles[$articleId[1]] = $values;
				}
			}
		}
		$contract->setArticles($articles);
		$repository->add($contract);
		$repository->flushDocumentManager();
	}
}

?>