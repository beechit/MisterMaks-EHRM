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
	 *
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
			} catch (\Exception $exception) {
				$this->logger->log(sprintf('Error "%s" action "%s". Error: %s', $action->getWorkflowName(), $action->getActionId(), $exception->getMessage()), LOG_ERR);
			}
		}
	}
}

?>