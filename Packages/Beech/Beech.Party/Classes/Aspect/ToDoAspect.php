<?php
namespace Beech\Party\Aspect;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Pieter Geurts <pieter@aleto.nl>
 * Date: 01-08-12 10:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * To-Do functionality for Beech packages
 *
 * @FLOW3\Aspect
 */
class ToDoAspect {

	/**
	 * @var \TYPO3\FLOW3\Persistence\PersistenceManagerInterface
	 * @FLOW3\inject
	 */
	protected $persistenceManager;

	/**
	 * @var \Beech\Party\Domain\Repository\ToDoRepository
	 * @FLOW3\Inject
	 */
	protected $toDoRepository;

	/**
	 * @var \TYPO3\FLOW3\Security\Context
	 * @FLOW3\Inject
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
	 * @param \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint
	 * @FLOW3\After("method(Beech\Party\Controller\Management\CompanyController->createAction())")
	 * @return void
	 */
	public function addToDoAfterCompanyCreate(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
		if ($this->partyAvailableInSecurityContext()) {
			$company = $joinPoint->getMethodArgument('newCompany');
			$this->createToDo('addAddress', $this->currentParty(), 'newAddress', 'management\company', serialize(array('company' => $this->persistenceManager->getIdentifierByObject($company))), 100, TRUE);
		}
	}



	/**
	 * @param \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint
	 * @FLOW3\After("method(Beech\Party\Controller\Management\CompanyController->createAddressAction())")
	 * @return void
	 */
	public function archiveToDoAfterAddressCreate(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
		$company = $this->persistenceManager->getIdentifierByObject($joinPoint->getMethodArgument('company'));
		$toDo = $this->toDoRepository->findByControllerActionAndArguments('management\company', 'newAddress', serialize(array('company' => $company)));
		if ($toDo instanceof \Beech\Party\Domain\Model\ToDo) {
			$this->toDoRepository->archiveToDo($toDo);
		}
	}

	/**
	 * @param string $task The task name
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $owner
	 * @param string $action The action to execute
	 * @param string $controller The controller to execute
	 * @param array $arguments The arguments
	 * @param integer $priority Priority of this task 0-100
	 * @param string $controllerName The controller name
	 * @param string $controllerAction The controller action
	 * @param string $controllerArgument The controller arguments
	 * @param boolean $userMayArchive True if user is allowed to archive this task manual
	 * @return void
	 */
	private function createToDo($task, \TYPO3\Party\Domain\Model\AbstractParty $owner, $controllerAction, $controllerName, $controllerArgument, $priority, $userMayArchive) {
		$todo = new \Beech\Party\Domain\Model\ToDo();

		$notification = new \Beech\Party\Domain\Model\Notification();
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
		$todo->setControllerArguments($controllerArgument);
		$todo->setUserMayArchive($userMayArchive);
		$todo->addNotification($notification);
		$this->toDoRepository->add($todo);
	}

}
?>