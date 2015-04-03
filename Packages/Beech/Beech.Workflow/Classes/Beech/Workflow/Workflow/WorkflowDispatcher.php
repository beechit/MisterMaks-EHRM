<?php
namespace Beech\Workflow\Workflow;

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