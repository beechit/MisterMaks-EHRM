<?php
namespace Beech\Workflow\Core;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 21:20
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 */
interface OutputHandlerInterface {

	/**
	 * Execute the output handler class
	 *
	 * @abstract
	 * @return mixed
	 */
	public function invoke();

	/**
	 * Set the Action that initiates this OutputHandler
	 * @param \Beech\Workflow\Domain\Model\Action $action
	 */
	public function setActionEntity(\Beech\Workflow\Domain\Model\Action $action);

	/**
	 * Set the action's target entity
	 *
	 * @param object $target
	 * @return void
	 */
	public function setTargetEntity($targetEntity);

}
