<?php
namespace Beech\Workflow\Workflow;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 10-09-2012 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Dispatch actions
 */
class ActionDispatcher {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	protected $logger;

	/**
	 * @var \Beech\Workflow\Domain\Repository\ActionRepository
	 * @Flow\Inject
	 */
	protected $actionRepository;

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * Collect and dispatch all active actions
	 * @return void
	 */
	public function run() {
		$actions = $this->actionRepository->findActive();

		$this->logger->log(sprintf('Run actions %d', count($actions)), LOG_DEBUG);

		if (!is_array($actions)) {
			return;
		}

		/** @var $action \Beech\Workflow\Domain\Model\Action */
		foreach ($actions as $action) {
			$this->logger->log(sprintf('Dispatch "%s" action "%s" [id=%s]', $action->getWorkflowName(), $action->getActionId(), $action->getId()), LOG_DEBUG);
			try {
				$action->dispatch();
				$this->actionRepository->update($action);
				$this->logger->log(sprintf('Dispatched "%s" action "%s" [status=%s]', $action->getWorkflowName(), $action->getActionId(), $action->getStatus()), LOG_DEBUG);
			} catch(\Exception $exception) {
				$this->logger->log(sprintf('Error "%s" action "%s". Error: %s', $action->getWorkflowName(), $action->getActionId(), $exception->getMessage()), LOG_ERR);
			}
		}
	}
}
?>