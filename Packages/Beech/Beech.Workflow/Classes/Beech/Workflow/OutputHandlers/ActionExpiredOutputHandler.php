<?php
namespace Beech\Workflow\OutputHandlers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 10-09-2012 22:53
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Beech\Workflow\Domain\Model\Action as Action,
	Beech\Workflow\Core\ActionInterface;

/**
 * ActionExpiredOutputHandler sets the status of an action to expired
 */
class ActionExpiredOutputHandler extends \Beech\Workflow\Core\AbstractOutputHandler implements \Beech\Workflow\Core\OutputHandlerInterface {

	/**
	 * @var \Beech\Workflow\Domain\Repository\ActionRepository
	 * @Flow\Inject
	 */
	protected $actionRepository;

	/**
	 * Execute this output handler class, set the status of the targetEntiry(action) to expired
	 *
	 * @return void
	 */
	public function invoke() {
		if ($this->targetEntity instanceof ActionInterface && in_array($this->targetEntity->getStatus(), array(Action::STATUS_NEW, Action::STATUS_STARTED))) {
			$this->targetEntity->setStatus(Action::STATUS_EXPIRED);
			$this->actionRepository->update($this->actionEntity);
		}
	}
}

?>