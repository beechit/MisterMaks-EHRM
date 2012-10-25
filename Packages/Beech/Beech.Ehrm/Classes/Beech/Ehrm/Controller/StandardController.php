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
	 * @var \Beech\Task\Domain\Repository\ToDoRepository
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

			$todosGroupedByPriority = array(
				'veryHigh' => array('label' => 'Very high', 'todos' => array(), 'class' => 'important'),
				'high' => array('label' => 'High', 'todos' => array(), 'class' => 'important'),
				'normal' => array('label' => 'Normal', 'todos' => array(), 'class' => 'warning'),
				'low' => array('label' => 'Low', 'todos' => array(), 'class' => 'success')
			);
			$allTodos = $this->toDoRepository->findByOwner($owner);

			foreach ($allTodos as $todo) {
				$stringPriority = '';

				if ($todo->getPriority() <= 50) {
					$stringPriority = \Beech\Task\Domain\Model\Todo::PRIORITY_LOW;
				} elseif ($todo->getPriority() <= 75) {
					$stringPriority = \Beech\Task\Domain\Model\Todo::PRIORITY_NORMAL;
				} elseif ($todo->getPriority() <= 100) {
					$stringPriority = \Beech\Task\Domain\Model\Todo::PRIORITY_HIGH;
				} elseif ($todo->getPriority() > 100) {
					$stringPriority = \Beech\Task\Domain\Model\Todo::PRIORITY_VERY_HIGH;
				}

				$todosGroupedByPriority[$stringPriority]['todos'][] = $todo;
			}

			$this->view->assign('groupedTodos', $todosGroupedByPriority);
		}
	}
}

?>