<?php
namespace Beech\Workflow\Workflow;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-05-2013 11:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

class WorkflowDispatcher {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	protected $logger;

	/**
	 * @var \Beech\Workflow\Domain\Repository\WorkflowRepository
	 * @Flow\Inject
	 */
	protected $workflowRepository;

	/**
	 * @var \Beech\Workflow\Domain\Repository\ActionRepository
	 * @Flow\Inject
	 */
	protected $actionRepository;

	/**
	 * @param string $action
	 * @param $object
	 */
	public function startWorkflow($action, $object, $currentParty) {

		$this->logger->log(sprintf('Party %s', get_class($currentParty)), LOG_DEBUG);

		$this->logger->log(sprintf('Check workflow for action "%s" on object "%s"', $action, get_class($object)), LOG_DEBUG);

		$workfows = $this->workflowRepository->findAllByTrigger($action, $object);

		/** @var $workflow \Beech\Workflow\Domain\Model\Workflow */
		foreach ($workfows as $workflow) {
			$this->logger->log(sprintf('Start workflow %s', $workflow->getName()), LOG_INFO);
			try {
				/** @var $action \Beech\Workflow\Domain\Model\Action */
				foreach ($workflow->getActions() as $action) {

					$action->setTarget($object);

						// security context is changed by AOP so set party manual
					$action->setCreatedBy($currentParty);

					$this->actionRepository->add($action);
					$this->logger->log(sprintf('Create action %s [%s]', $action->getActionId(), $action->getId()), LOG_DEBUG);
				}
			} catch (\Exception $exception) {
				$this->logger->log(sprintf('Couldn\'t start workflow "%s"! Exception: %s', $workflow->getName(), $exception->getMessage()), LOG_ERR);
			}
		}
	}

}

?>