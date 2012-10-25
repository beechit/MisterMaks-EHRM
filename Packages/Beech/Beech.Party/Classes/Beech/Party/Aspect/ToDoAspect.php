<?php
namespace Beech\Party\Aspect;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-08-12 10:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * To-Do functionality for Beech packages
 *
 * @Flow\Aspect
 */
class ToDoAspect {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

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
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	protected function currentParty() {
		return $this->securityContext->getAccount()->getParty();
	}

	/**
	 * @return boolean
	 */
	protected function partyAvailableInSecurityContext() {
		return $this->securityContext->getAccount()->getParty() instanceof \TYPO3\Party\Domain\Model\AbstractParty;
	}

	/**
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint
	 * @Flow\After("method(Beech\Party\Controller\Management\CompanyController->createAddressAction())")
	 * @return void
	 */
	public function archiveToDoAfterAddressCreate(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		$company = $this->persistenceManager->getIdentifierByObject($joinPoint->getMethodArgument('company'));
		$toDo = $this->toDoRepository->findOneByControllerActionAndArguments('management\company', 'newAddress', serialize(array('company' => $company)));
		if ($toDo instanceof \Beech\Party\Domain\Model\ToDo) {
			$this->toDoRepository->archiveToDo($toDo);
		}
	}

	/**
	 * @param string $task The task name
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $owner
	 * @param string $controllerAction The action to execute
	 * @param string $controllerName The controller to execute
	 * @param array $controllerArguments The arguments
	 * @param integer $priority Priority of this task 0-100
	 * @param boolean $userMayArchive True if user is allowed to archive this task manual
	 * @return void
	 */
	private function createToDo($task, \TYPO3\Party\Domain\Model\AbstractParty $owner, $controllerAction, $controllerName, $controllerArguments, $priority, $userMayArchive) {
		$notification = new \Beech\Ehrm\Domain\Model\Notification();
		$notification->setLabel($task);
		$notification->setSticky(TRUE);
		$notification->setCloseable(TRUE);
		$notification->setParty($this->currentParty());

		$todo = new \Beech\Party\Domain\Model\ToDo();
		$todo->setTask($task);
		$todo->setOwner($owner);

		if ($this->partyAvailableInSecurityContext()) {
			$todo->setStarter($this->currentParty());
		}

		$todo->setPriority($priority);
		$todo->setControllerName($controllerName);
		$todo->setControllerAction($controllerAction);
		$todo->setControllerArguments($controllerArguments);
		$todo->setUserMayArchive($userMayArchive);
		$todo->addNotification($notification);
		$this->toDoRepository->add($todo);
	}

}
?>