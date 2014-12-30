<?php
namespace Beech\Workflow\Aspect;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */
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
	 * @Flow\After("method(Beech\.*\Domain\Repository\.*Repository->(add|update|remove)())")
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