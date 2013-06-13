<?php
namespace Beech\Workflow\Core;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 31-05-2013 15:20
 * All code (c) Beech Applications B.V. all rights reserved
 */


use TYPO3\Flow\Annotations as Flow;

abstract class AbstractOutputHandler implements \Beech\Workflow\Core\OutputHandlerInterface {

	/**
	 * The Action
	 * @var \Beech\Workflow\Domain\Model\Action
	 */
	protected $action;

	/**
	 * The action's target entity
	 *
	 * @var object
	 */
	protected $targetEntity;

	/**
	 * Set the Action that initiates this OutputHandler
	 * @param \Beech\Workflow\Domain\Model\Action $action
	 */
	public function setActionEntity(\Beech\Workflow\Domain\Model\Action $action) {
		$this->action = $action;
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

}