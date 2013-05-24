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
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * @var \Beech\Workflow\Domain\Repository\WorkflowRepository
	 * @Flow\Inject
	 */
	protected $workflowRepository;

	/**
	 * @param string $action
	 * @param $object
	 */
	public function startWorkflow($action, $object) {

		$this->logger->log(sprintf('Check workflow for action "%s" on object "%s"', $action,get_class($object)), LOG_DEBUG);

		$workfows = $this->workflowRepository->findAllByTrigger($action, $object);

		/** @var $workflow \Beech\Workflow\Domain\Model\Workflow */
		foreach($workfows as $workflow) {
			$this->logger->log(sprintf('Start workflow %s', $workflow->getName()), LOG_INFO);
		}
	}
}