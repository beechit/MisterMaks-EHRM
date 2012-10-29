<?php
namespace Beech\WorkFlow\Tests\Functional;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 21:05
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Beech\WorkFlow\Domain\Model\Action,
	Beech\Party\Domain\Model\Company,
	Beech\Task\Domain\Model\ToDo;

/**
 * Test the actual persistence of actions
 */
class PersistenceTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	* @var boolean
	*/
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\WorkFlow\Domain\Repository\ActionRepository
	 */
	protected $actionRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 */
	protected $companyRepository;

	/**
	 * @var \Beech\Task\Domain\Repository\ToDoRepository
	 */
	protected $toDoRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @var \TYPO3\Flow\Security\AccountFactory
	 */
	protected $accountFactory;

	/**
	 * Setup a testcase
	 */
	public function setUp() {
		parent::setUp();
		$this->actionRepository = $this->objectManager->get('\Beech\WorkFlow\Domain\Repository\ActionRepository');
		$this->companyRepository = $this->objectManager->get('\Beech\Party\Domain\Repository\CompanyRepository');
		$this->toDoRepository = $this->objectManager->get('\Beech\Task\Domain\Repository\ToDoRepository');
		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
		$this->accountRepository = $this->objectManager->get('TYPO3\Flow\Security\AccountRepository');
		$this->accountFactory = $this->objectManager->get('TYPO3\Flow\Security\AccountFactory');
	}

	/**
	 * @test
	 */
	public function anActionCanBeCreatedAndPersisted() {
		$action = new Action();
		$this->actionRepository->add($action);

		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->actionRepository->countAll());
	}

	/**
	 * @test
	 */
	public function aPropertyValidatorCanBePersistedAndRetrieved() {
		$action = new Action();
		$namePropertyNotEmptyValidator = new \Beech\WorkFlow\Validators\Property\NotEmptyValidator();
		$action->addValidator($namePropertyNotEmptyValidator);
		$this->actionRepository->add($action);

		$this->persistenceManager->persistAll();

		$persistedAction = $this->actionRepository->findAll()->getFirst();

		$this->assertEquals($action, $persistedAction);
	}

	/**
	 * @test
	 */
	public function aTargetEntityCanBePersistedAndRetrieved() {
		$this->assertEquals(0, $this->companyRepository->countAll());
		$this->assertEquals(0, $this->actionRepository->countAll());

		$company = $this->createCompany('Foo', 1, 1, 'type', 'description', 'bar');
		$this->companyRepository->add($company);

		$this->persistenceManager->persistAll();
		$this->assertEquals(1, $this->companyRepository->countAll());

		$action = new Action();
		$action->setTarget($company);
		$this->actionRepository->add($action);

		$this->persistenceManager->persistAll();
		$this->assertEquals(1, $this->actionRepository->countAll());

		$persistedAction = $this->actionRepository->findAll()->getFirst();
		$this->assertEquals($persistedAction->getTargetClassName(), 'Beech\Party\Domain\Model\Company');
		$this->assertEquals($persistedAction->getTargetIdentifier(), $this->persistenceManager->getIdentifierByObject($company));
	}

	/**
	 * @test
	 */
	public function actionsCanBeRetrievedByStatus() {
		$statusList = array(
			Action::STATUS_EXPIRED,
			Action::STATUS_NEW,
			Action::STATUS_EXPIRED,
			Action::STATUS_FINISHED,
			Action::STATUS_NEW,
			Action::STATUS_EXPIRED,
			Action::STATUS_STARTED,
			Action::STATUS_NEW,
			Action::STATUS_NEW,
			Action::STATUS_TERMINATED,
			Action::STATUS_STARTED
		);

		foreach ($statusList as $status) {
			$action = new Action();
			$action->setStatus($status);
			$this->actionRepository->add($action);
		}

		$this->persistenceManager->persistAll();

			// test the findActive method
		$this->assertEquals(6, $this->actionRepository->findActive()->count());

			// test the countByStatus method
		$this->assertEquals(3, $this->actionRepository->countByStatus(Action::STATUS_EXPIRED));
		$this->assertEquals(4, $this->actionRepository->countByStatus(Action::STATUS_NEW));
		$this->assertEquals(1, $this->actionRepository->countByStatus(Action::STATUS_FINISHED));
		$this->assertEquals(1, $this->actionRepository->countByStatus(Action::STATUS_TERMINATED));
		$this->assertEquals(2, $this->actionRepository->countByStatus(Action::STATUS_STARTED));
	}

	/**
	 * @test
	 */
	public function anEntityOutputHandlerCanPersistDifferentTypesOfEntities() {
		$outputHandler = new \Beech\WorkFlow\OutputHandlers\EntityOutputHandler();
		$outputHandler->setEntity($this->createTodoOutput('addAddress', $this->createPerson(), 'newAddress', 'management\company', serialize(array()), 100, TRUE));
		$outputHandler->invoke();

		$this->assertEquals(0, $this->toDoRepository->countAll());

		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->toDoRepository->countAll());

		$outputHandler = new \Beech\WorkFlow\OutputHandlers\EntityOutputHandler();
		$outputHandler->setEntity($this->createCompany('Foo', 1, 1, 'type', 'description', 'bar'));
		$outputHandler->invoke();

		$this->assertEquals(0, $this->companyRepository->countAll());

		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->companyRepository->countAll());
	}

	/**
	 * @test
	 */
	public function aPreConditionCanBePersistedAndRetrieved() {
		$action = new Action();

		$preCondition = new \Beech\WorkFlow\PreConditions\DatePreCondition();
		$preCondition->setValue(new \DateTime('yesterday'));
		$preCondition->setMatchCondition(\Beech\WorkFlow\PreConditions\DatePreCondition::MATCH_CONDITION_SMALLER_THEN);

		$action->addPreCondition($preCondition);
		$this->actionRepository->add($action);

		$this->persistenceManager->persistAll();

		$persistedAction = $this->actionRepository->findAll()->getFirst();

		$this->assertEquals($action, $persistedAction);
	}

	/**
	 * @test
	 */
	public function theActionExpiredOutputHandlerCanExpireAnAction() {
			// Add an action to test with
		$action = new Action();

		$this->actionRepository->add($action);
		$this->persistenceManager->persistAll();
		$persistedAction = $this->actionRepository->findAll()->getFirst();

		$this->assertEquals(1, $this->actionRepository->countByStatus(Action::STATUS_NEW));

		$outputHandler = new \Beech\WorkFlow\OutputHandlers\ActionExpiredOutputHandler();
		$outputHandler->setActionEntity($persistedAction);
		$outputHandler->invoke();

		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->actionRepository->countByStatus(Action::STATUS_EXPIRED));
	}

	/**
	 * @test
	 */
	public function theActionOutputHandlerCanAddANewAction() {
		$company = $this->createCompany('Foo', 1, 1, 'type', 'description', 'bar');

		$outputHandler = new \Beech\WorkFlow\OutputHandlers\ActionOutputHandler();
		$outputHandler->setWorkflowName('ActionOutputHandlerTest');
		$outputHandler->setResourcePath(__DIR__ . '/Fixtures/');
		$outputHandler->setTargetEntity($company);

		$action = new Action();
		$action->addOutputHandler($outputHandler);
		$this->actionRepository->add($action);

		$this->persistenceManager->persistAll();
		$this->persistenceManager->clearState();

		$this->assertEquals(1, $this->actionRepository->countAll());
		$this->assertEquals(1, $this->actionRepository->countByStatus(Action::STATUS_NEW));

			// Run the action, which will effectively result in a new action being added
		$dispatcher = new \Beech\WorkFlow\WorkFlow\ActionDispatcher();
		$dispatcher->run();

		$this->persistenceManager->persistAll();
		$this->persistenceManager->clearState();

		$this->assertEquals(2, $this->actionRepository->countAll());
	}

	/**
	 * @test
	 */
	public function anActionWithMultiplePreconditionsAndMultipleValidatorsAndMultipleOutputhandlersCanBePersistedAndDispatched() {
			// Add company we can test the validator on
		$company = $this->createCompany('Foo', 1, 1, 'type', 'description', 'bar');
		$this->companyRepository->add($company);
		$this->persistenceManager->persistAll();
		$this->assertEquals(1, $this->companyRepository->countAll());

		$persistedCompany = $this->companyRepository->findAll()->getFirst();

			// Add 2 preconditions which will be met
		$preCondition = new \Beech\WorkFlow\PreConditions\DatePreCondition();
		$preCondition->setValue(new \DateTime('tomorrow'));
		$preCondition->setMatchCondition(\Beech\WorkFlow\PreConditions\DatePreCondition::MATCH_CONDITION_GREATER_THEN);
		$preConditions[] = $preCondition;

		$preCondition = new \Beech\WorkFlow\PreConditions\DatePreCondition();
		$preCondition->setValue(new \DateTime('yesterday'));
		$preCondition->setMatchCondition(\Beech\WorkFlow\PreConditions\DatePreCondition::MATCH_CONDITION_SMALLER_THEN);
		$preConditions[] = $preCondition;

			// Add 2 validators which will pass
		$validator = new \Beech\WorkFlow\Validators\Property\NotEmptyValidator();
		$validator->setPropertyName('name');
		$validator->setTargetEntity($persistedCompany);
		$validators[] = $validator;

		$validator = new \Beech\WorkFlow\Validators\Property\NotEmptyValidator();
		$validator->setPropertyName('description');
		$validator->setTargetEntity($persistedCompany);
		$validators[] = $validator;

		$person = $this->createPerson();

			// Add 2 ToDo outputhandlers
		$outputHandler = new \Beech\WorkFlow\OutputHandlers\EntityOutputHandler();
		$outputHandler->setEntity($this->createTodoOutput('addAddress', $person, 'newAddress', 'management\company', serialize(array('company' => $this->persistenceManager->getIdentifierByObject($persistedCompany))), 100, TRUE));
		$outputHandlers[] = $outputHandler;

		$outputHandler = new \Beech\WorkFlow\OutputHandlers\EntityOutputHandler();
		$outputHandler->setEntity($this->createTodoOutput('addHomeNumber', $person, 'newAddress', 'management\company', serialize(array('company' => $this->persistenceManager->getIdentifierByObject($persistedCompany))), 100, TRUE));
		$outputHandlers[] = $outputHandler;

			// Create the action
		$action = $this->createAction($preConditions, $validators, $outputHandlers);

		$this->persistenceManager->persistAll();

		$persistedAction = $this->actionRepository->findAll()->getFirst();
		$this->assertEquals($action, $persistedAction);

			// Make sure no todo was added already
		$this->assertEquals(0, $this->toDoRepository->countAll());

		$persistedAction->dispatch();

		$this->persistenceManager->persistAll();

			// Test if a todo entity was added
		$this->assertEquals(2, $this->toDoRepository->countAll());

			// Check the status of the action
		$persistedAction = $this->actionRepository->findAll()->getFirst();
		$this->assertEquals(\Beech\WorkFlow\Domain\Model\Action::STATUS_FINISHED, $persistedAction->getStatus());
	}

	/**
	 * @param string $name
	 * @param string $companyNumber
	 * @param string $chamberOfCommerceNumber
	 * @param string $type
	 * @param string $description
	 * @param string $legalForm
	 * @return \Beech\Party\Domain\Model\Company
	 */
	protected function createCompany($name, $companyNumber, $chamberOfCommerceNumber, $type, $description, $legalForm) {
		$company = new Company();
		$company->setName($name);
		$company->setCompanyNumber($companyNumber);
		$company->setChamberOfCommerceNumber($chamberOfCommerceNumber);
		$company->setCompanyType($type);
		$company->setDescription($description);
		$company->setLegalForm($legalForm);

		return $company;
	}

	/**
	 * @param string $task The task name
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $owner
	 * @param string $controllerAction The action to execute
	 * @param string $controllerName The controller to execute
	 * @param array $controllerArguments The arguments
	 * @param integer $priority Priority of this task 0-100
	 * @param boolean $userMayArchive True if user is allowed to archive this task manual
	 * @return \Beech\Task\Domain\Model\ToDo
	 */
	protected function createToDoOutput($task, \TYPO3\Party\Domain\Model\AbstractParty $owner, $controllerAction, $controllerName, $controllerArguments, $priority, $userMayArchive) {
		$todo = new \Beech\Task\Domain\Model\ToDo();
		$todo->setTask($task);
		$todo->setOwner($owner);
		$todo->setPriority($priority);
		$todo->setControllerName($controllerName);
		$todo->setControllerAction($controllerAction);
		$todo->setControllerArguments($controllerArguments);
		$todo->setUserMayArchive($userMayArchive);

		return $todo;
	}

	/**
	 * Add a person to we can test with
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	protected function createPerson() {
		$person = new \Beech\Party\Domain\Model\Person();
		$account = $this->accountFactory->createAccountWithPassword(uniqid() . '@domain.ext', $this->persistenceManager->getIdentifierByObject($person));
		$this->accountRepository->add($account);
		$person->addAccount($account);

		$this->personRepository->add($person);
		$this->persistenceManager->persistAll();
		return $person;
	}

	/**
	 * Create an action to test with
	 * @param array $preConditions
	 * @param array $validators
	 * @param array $outputHandlers
	 * @param string $status
	 * @return \Beech\WorkFlow\Domain\Model\Action
	 */
	protected function createAction(array $preConditions, array $validators, array $outputHandlers, $status = NULL) {
		$action = new Action();

		if ($status) {
			$action->setStatus($status);
		}

		foreach ($preConditions as $preCondition) {
			$action->addPreCondition($preCondition);
		}

		foreach ($validators as $validator) {
			$action->addValidator($validator);
		}

		foreach ($outputHandlers as $outputHandler) {
			$action->addOutputHandler($outputHandler);
		}

		$this->actionRepository->add($action);
		$this->persistenceManager->persistAll();
		return $action;
	}
}
?>