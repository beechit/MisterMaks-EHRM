<?php
namespace Beech\WorkFlow\OutputHandlers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 20-09-2012 22:53
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Beech\WorkFlow\WorkFlow\ActionFactory as ActionFactory;

/**
 * ActionOutputHandler persists a new Action entity
 */
class ActionOutputHandler implements \Beech\WorkFlow\Core\OutputHandlerInterface {

	/**
	 * @var \Beech\WorkFlow\Domain\Repository\ActionRepository
	 * @FLOW3\Inject
	 */
	protected $actionRepository;

	/**
	 * Name of the workflow to create
	 *
	 * @var string
	 */
	protected $workflowName;

	/**
	 * Path where the workflow configuration file can be found
	 *
	 * @var string
	 */
	protected $resourcePath;

	/**
	 * The action's target entity
	 *
	 * @var object
	 */
	protected $targetEntity;

	/**
	 * Set the name of the workflow
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
	 * Set the action's target entity
	 *
	 * @param object $target
	 * @return void
	 */
	public function setTargetEntity($targetEntity) {
		$this->targetEntity = $targetEntity;
	}

	/**
	 * Execute this output handler class, create a new action and persist it
	 * @return void
	 */
	public function invoke() {
		$factory = new ActionFactory($this->workflowName, $this->resourcePath);
		$actions  = $factory->create();
		foreach ($actions as $action) {
			$action->setTarget($this->targetEntity);
			$this->actionRepository->add($action);
		}
	}
}
?>