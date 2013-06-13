<?php
namespace Beech\Workflow\OutputHandlers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 10-09-2012 22:53
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM,
	Beech\Workflow\Domain\Model\Action as Action;

/**
 * ActionExpiredOutputHandler sets the status of an action to expired
 * @ODM\EmbeddedDocument
 * @ODM\Document
 */
class ActionExpiredOutputHandler implements \Beech\Workflow\Core\OutputHandlerInterface {

	/**
	 * @var \Beech\Workflow\Domain\Repository\ActionRepository
	 * @Flow\Inject
	 */
	protected $actionRepository;

	/**
	 * The entitiy to persist
	 * @var \Beech\Workflow\Domain\Model\Action
	 */
	protected $actionEntity;

	/**
	 * Set the entity to persist
	 * @param \Beech\Workflow\Domain\Model\Action $action
	 */
	public function setActionEntity(\Beech\Workflow\Domain\Model\Action $action) {
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