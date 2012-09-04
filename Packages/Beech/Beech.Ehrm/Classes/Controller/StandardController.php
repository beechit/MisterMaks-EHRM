<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Rens Admiraal <rens@beech.it>
 * Date: 03-05-12 22:50
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 * Standard controller for the Beech.Ehrm package
 *
 * @FLOW3\Scope("singleton")
 */
class StandardController extends AbstractController {

	/**
	 * @FLOW3\Inject
	 * @var \Beech\Party\Domain\Repository\ToDoRepository
	 */
	protected $toDoRepository;

	/**
	 * @var \TYPO3\FLOW3\Security\Context
	 * @FLOW3\Inject
	 */
	protected $securityContext;

	/**
	 *
	 */
	public function indexAction() {
		$owner = $this->securityContext->getAccount()->getParty();
		$this->view->assign('todos', $this->toDoRepository->findByOwner($owner));
	}
}

?>