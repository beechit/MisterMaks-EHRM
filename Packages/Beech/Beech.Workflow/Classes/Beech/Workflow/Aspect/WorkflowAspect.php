<?php
namespace Beech\Workflow\Aspect;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 1/16/13 12:09 AM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Aspect
 */
class WorkflowAspect {

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $securityContext;

	/**
	 * @Flow\After("method(Beech\Party\Domain\Repository\.*Repository->(add|update|remove)())")
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint The current join point
	 * @return void
	 */
	public function modelAspect(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {

			// find object
		$arguments = $joinPoint->getMethodArguments();
		$object = array_shift($arguments);
		$action = $joinPoint->getMethodName();

			// object found than delegate info to workflow dispatcher
		if ($object) {
			$workflowDispatcher = new \Beech\Workflow\Workflow\WorkflowDispatcher();
			$workflowDispatcher->startWorkflow($action, $object, $this->getCurrentParty());
		}
	}

	/**
	 * Get current party
	 *
	 * @return null|\TYPO3\Party\Domain\Model\AbstractParty
	 */
	protected function getCurrentParty() {
		if($this->securityContext->canBeInitialized() && $this->securityContext->getAccount()) {
			return $this->securityContext->getAccount()->getParty();
		} else {
			return NULL;
		}
	}
}

?>