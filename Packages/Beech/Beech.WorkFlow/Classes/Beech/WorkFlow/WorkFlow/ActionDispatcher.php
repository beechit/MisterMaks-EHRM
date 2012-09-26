<?php
namespace Beech\WorkFlow\WorkFlow;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 10-09-2012 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Dispatch actions
 */
class ActionDispatcher {

	/**
	 * @var \Beech\WorkFlow\Domain\Repository\ActionRepository
	 * @FLOW3\Inject
	 */
	protected $actionRepository;

	/**
	 * Collect and dispatch all active actions
	 * @return void
	 */
	public function run() {
		$actions = $this->actionRepository->findActive();

		foreach ($actions as $action) {
			$action->dispatch();
			$this->actionRepository->update($action);
		}
	}
}
?>