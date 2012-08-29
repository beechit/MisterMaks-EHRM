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
			$this->createTask('addAddress', $this->currentParty(), 'new', 'management\address', array('company' => $this->persistenceManager->getIdentifierByObject($company)), 100);
		}
	}

	/**
	 * @param \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint
	 * @FLOW3\After("method(Beech\Party\Controller\Management\CompanyController->createAddressAction())")
	 * @return void
	 */
	public function removeToDoAfterAddressCreate(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
		$company = $this->persistenceManager->getIdentifierByObject($joinPoint->getMethodArgument('company'));
		$this->archiveTask('management\address', 'new', array('company' => $company));
	}

	/**
	 * @param string $task The task name
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $owner
	 * @param string $action The action to execute
	 * @param string $controller The controller to execute
	 * @param array $arguments The arguments
	 * @param integer $priority Priority of this task 0-100
	 * @return void
	 */
	protected function createTask($task, \TYPO3\Party\Domain\Model\AbstractParty $owner, $action, $controller, $arguments, $priority) {
		$todo = new \Beech\Party\Domain\Model\ToDo();

		$todo->setTask($task);
		$todo->setAction($action);
		$todo->setOwner($owner);
		$todo->setStarter($this->currentParty());
		$todo->setController($controller);
		$todo->setArguments(serialize($arguments));
		$todo->setPriority($priority);

		$this->toDoRepository->add($todo);
	}

	/**
	 * @param string $controller The controller
	 * @param string $action The action
	 * @param array $arguments The arguments
	 * @return void
	 */
	protected function archiveTask($controller, $action, $arguments) {
		$arguments = serialize($arguments);
		$this->toDoRepository->archiveTask($controller, $action, $arguments);
	}
}
?>