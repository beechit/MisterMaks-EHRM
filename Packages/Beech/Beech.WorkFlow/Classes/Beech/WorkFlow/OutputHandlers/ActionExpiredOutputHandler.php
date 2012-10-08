<?php
namespace Beech\WorkFlow\OutputHandlers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 10-09-2012 22:53
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\WorkFlow\Domain\Model\Action as Action;

/**
 * ActionExpiredOutputHandler sets the status of an action to expired
 */
class ActionExpiredOutputHandler implements \Beech\WorkFlow\Core\OutputHandlerInterface {

	/**
	 * @var \Beech\WorkFlow\Domain\Repository\ActionRepository
	 * @Flow\Inject
	 */
	protected $actionRepository;

	/**
	 * The entitiy to persist
	 * @var \Beech\WorkFlow\Domain\Model\Action
	 */
	protected $actionEntity;

	/**
	 * Set the entity to persist
	 * @param \Beech\WorkFlow\Domain\Model\Action $action
	 */
	public function setActionEntity(\Beech\WorkFlow\Domain\Model\Action $action) {
		$this->actionEntity = $action;
	}

	/**
	 * Execute this output handler class, set the status of the action to expired
	 * @return void
	 */
	public function invoke() {
		$this->actionEntity->setStatus(Action::STATUS_EXPIRED);
		$this->actionRepository->update($this->actionEntity);
	}
}
?>