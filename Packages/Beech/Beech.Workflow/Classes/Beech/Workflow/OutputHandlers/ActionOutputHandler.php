<?php
namespace Beech\Workflow\OutputHandlers;

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

use TYPO3\Flow\Annotations as Flow,
	Beech\Workflow\Workflow\ActionFactory as ActionFactory;

/**
 * ActionOutputHandler persists a new Action entity
 */
class ActionOutputHandler extends \Beech\Workflow\Core\AbstractOutputHandler implements \Beech\Workflow\Core\OutputHandlerInterface {

	/**
	 * @var \Beech\Workflow\Domain\Repository\ActionRepository
	 * @Flow\Inject
	 */
	protected $actionRepository;

	/**
	 * Name of the Workflow to create
	 *
	 * @var string
	 */
	protected $WorkflowName;

	/**
	 * Set the name of the Workflow
	 *
	 * @param string $workflowName
	 * @return void
	 */
	public function setWorkflowName($workflowName) {
		$this->workflowName = $workflowName;
	}

	/**
	 * Set the path of the workflow
	 *
	 * @param string $workflowPath
	 * @return void
	 */
	public function setResourcePath($resourcePath) {
		$this->resourcePath = $resourcePath;
	}

	/**
	 * Execute this output handler class, create a new action and persist it
	 *
	 * @return void
	 */
	public function invoke() {
		$factory = new ActionFactory();
		$factory->setWorkflowName($this->workflowName);
		$actions = $factory->create();
		foreach ($actions as $action) {
			$action->setTarget($this->targetEntity);
			$this->actionRepository->add($action);
		}
	}
}

?>