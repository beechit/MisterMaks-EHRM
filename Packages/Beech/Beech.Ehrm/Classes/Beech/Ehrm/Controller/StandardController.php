<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-05-12 22:50
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Standard controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class StandardController extends AbstractController {

	/**
	 * @var \Beech\Party\Domain\Repository\ToDoRepository
	 * @Flow\Inject
	 */
	protected $toDoRepository;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 *
	 */
	public function indexAction() {
		$ownerAccount = $this->securityContext->getAccount();
		if ($ownerAccount instanceof \TYPO3\Flow\Security\Account) {
			$owner = $ownerAccount->getParty();
			$this->view->assign('todos', $this->toDoRepository->findByOwner($owner));
		}
	}
}

?>