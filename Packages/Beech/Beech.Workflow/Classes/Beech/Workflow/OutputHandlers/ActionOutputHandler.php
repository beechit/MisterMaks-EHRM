<?php
namespace Beech\Workflow\OutputHandlers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 20-09-2012 22:53
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Beech\Workflow\Workflow\ActionFactory as ActionFactory;

/**
 * ActionOutputHandler persists a new Action entity
 */
class ActionOutputHandler extends \Beech\Workflow\Core\OutputHandlerAbstract implements \Beech\Workflow\Core\OutputHandlerInterface {

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
	 * @return void
	 */
	public function invoke() {
		$factory = new ActionFactory();
		$factory->setWorkflowName($this->workflowName);
		$actions  = $factory->create();
		foreach ($actions as $action) {
			$action->setTarget($this->targetEntity);
			$this->actionRepository->add($action);
		}
	}
}
?>