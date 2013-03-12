<?php
namespace Beech\WorkFlow\Tests\Functional;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 21:05
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Beech\WorkFlow\Domain\Model\Action;

/**
 * Test the actual persistence of actions
 */
class PersistenceTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	* @var boolean
	*/
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var boolean
	 */
	protected $testableSecurityEnabled = TRUE;

	/**
	 * @var \Beech\WorkFlow\Domain\Repository\ActionRepository
	 */
	protected $actionRepository;

	/**
	 * @var \Beech\WorkFlow\Tests\Functional\Fixtures\Domain\Repository\EntityRepository
	 */
	protected $entityRepository;

	/**
	 * @var \Beech\WorkFlow\Tests\Functional\Fixtures\Domain\Repository\CompanyRepository
	 */
	protected $companyRepository;

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

		$person = new \Beech\Party\Domain\Model\Person();
		$person->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'John', NULL, 'Doe'));

		$account = new \TYPO3\Flow\Security\Account();
		$account->setParty($person);

		$this->authenticateAccount($account);

		$this->actionRepository = $this->objectManager->get('Beech\WorkFlow\Domain\Repository\ActionRepository');
		$this->actionRepository->injectDocumentManagerFactory($this->documentManagerFactory);

		$this->entityRepository = $this->objectManager->get('Beech\WorkFlow\Tests\Functional\Fixtures\Domain\Repository\EntityRepository');
		$this->companyRepository = $this->objectManager->get('Beech\WorkFlow\Tests\Functional\Fixtures\Domain\Repository\CompanyRepository');
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

		$this->documentManager->flush();

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

		$this->documentManager->flush();

		$persistedActions = $this->actionRepository->findAll();

		$this->assertEquals($action, $persistedActions[0]);
	}

	/**
	 * @test
	 */
	public function aTargetEntityCanBePersistedAndRetrieved() {
		$this->assertEquals(0, $this->entityRepository->countAll());
		$this->assertEquals(0, $this->actionRepository->countAll());

		$entity = new \Beech\WorkFlow\Tests\Functional\Fixtures\Domain\Model\Entity();
		$entity->setTitle('foo');
		$this->entityRepository->add($entity);

		$this->persistenceManager->persistAll();
		$this->persistenceManager->clearState();
		$this->assertEquals(1, $this->entityRepository->countAll());

		$action = new Action();
		$action->setTarget($entity);
		$this->actionRepository->add($action);

		$this->documentManager->flush();
		$this->assertEquals(1, $this->actionRepository->countAll());

		$persistedActions = $this->actionRepository->findAll();
		$this->assertEquals($persistedActions[0]->getTargetClassName(), 'Beech\WorkFlow\Tests\Functional\Fixtures\Domain\Model\Entity');
		$this->assertEquals($persistedActions[0]->getTargetIdentifier(), $this->persistenceManager->getIdentifierByObject($entity));
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

		$this->documentManager->flush();

			// test the findActive method
		$this->assertEquals(6, count($this->actionRepository->findActive()));

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

		$entityOutput = new \Beech\WorkFlow\Tests\Functional\Fixtures\Domain\Model\Entity();
		$entityOutput->setTitle('foo');
		$outputHandler->setEntity($entityOutput);

		$this->assertEquals(0, $this->entityRepository->countAll());
		$outputHandler->invoke();
		$this->persistenceManager->persistAll();
		$this->assertEquals(1, $this->entityRepository->countAll());

		$company = new \Beech\WorkFlow\Tests\Functional\Fixtures\Domain\Model\Company();
		$company->setTitle('foo');

		$outputHandler = new \Beech\WorkFlow\OutputHandlers\EntityOutputHandler();
		$outputHandler->setEntity($company);

		$this->assertEquals(0, $this->companyRepository->countAll());
		$outputHandler->invoke();
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

		$this->documentManager->flush();

		$persistedActions = $this->actionRepository->findAll();

		$this->assertEquals($action, $persistedActions[0]);
	}

	/**
	 * @test
	 */
	public function theActionExpiredOutputHandlerCanExpireAnAction() {
		$this->actionRepository->add(new Action());
		$this->documentManager->flush();
		$persistedActions = $this->actionRepository->findAll();

		$this->assertEquals(1, $this->actionRepository->countByStatus(Action::STATUS_NEW));

		$outputHandler = new \Beech\WorkFlow\OutputHandlers\ActionExpiredOutputHandler();
		$outputHandler->setActionEntity($persistedActions[0]);
		$outputHandler->invoke();

		$this->documentManager->flush();

		$this->assertEquals(1, $this->actionRepository->countByStatus(Action::STATUS_EXPIRED));
	}

	/**
	 * @test
	 */
	public function theActionOutputHandlerCanAddANewAction() {
		$entity = new \Beech\WorkFlow\Tests\Functional\Fixtures\Domain\Model\Entity();
		$entity->setTitle('foo');

		$outputHandler = new \Beech\WorkFlow\OutputHandlers\ActionOutputHandler();
		$outputHandler->setWorkflowName('ActionOutputHandlerTest');
		$outputHandler->setResourcePath(__DIR__ . '/Fixtures/');
		$outputHandler->setTargetEntity($entity);

		$action = new Action();
		$action->addOutputHandler($outputHandler);
		$this->actionRepository->add($action);

		$this->documentManager->flush();

		$this->assertEquals(1, $this->actionRepository->countAll());
		$this->assertEquals(1, $this->actionRepository->countByStatus(Action::STATUS_NEW));

			// Run the action, which will effectively result in a new action being added
		$dispatcher = new \Beech\WorkFlow\WorkFlow\ActionDispatcher();
		$dispatcher->run();

		$this->documentManager->flush();

		$this->assertEquals(2, $this->actionRepository->countAll());
	}

	/**
	 * @test
	 */
	public function anActionWithMultiplePreconditionsAndMultipleValidatorsAndMultipleOutputhandlersCanBePersistedAndDispatched() {
		$entity = new \Beech\WorkFlow\Tests\Functional\Fixtures\Domain\Model\Entity();
		$entity->setTitle('foo');
		$entity->setLabel('bar');

		$this->entityRepository->add($entity);
		$this->persistenceManager->persistAll();

		$this->assertEquals(1, $this->entityRepository->countAll());
		$persistedEntity = $this->entityRepository->findAll()->getFirst();

		$action = new Action();

			// Add 2 preconditions which will be met
		$preCondition = new \Beech\WorkFlow\PreConditions\DatePreCondition();
		$preCondition->setValue(new \DateTime('tomorrow'));
		$preCondition->setMatchCondition(\Beech\WorkFlow\PreConditions\DatePreCondition::MATCH_CONDITION_GREATER_THEN);
		$action->addPreCondition($preCondition);

		$preCondition = new \Beech\WorkFlow\PreConditions\DatePreCondition();
		$preCondition->setValue(new \DateTime('yesterday'));
		$preCondition->setMatchCondition(\Beech\WorkFlow\PreConditions\DatePreCondition::MATCH_CONDITION_SMALLER_THEN);
		$action->addPreCondition($preCondition);

			// Add 2 validators which will pass
		$validator = new \Beech\WorkFlow\Validators\Property\NotEmptyValidator();
		$validator->setPropertyName('title');
		$validator->setTargetEntity($persistedEntity);
		$action->addValidator($validator);

		$validator = new \Beech\WorkFlow\Validators\Property\NotEmptyValidator();
		$validator->setPropertyName('label');
		$validator->setTargetEntity($persistedEntity);
		$action->addValidator($validator);

			// Add 2 Task outputhandlers
		$outputHandler = new \Beech\WorkFlow\OutputHandlers\EntityOutputHandler();
		$company = new \Beech\WorkFlow\Tests\Functional\Fixtures\Domain\Model\Company();
		$company->setTitle('bar');
		$outputHandler->setEntity($company);
		$action->addOutputHandler($outputHandler);

		$outputHandler = new \Beech\WorkFlow\OutputHandlers\EntityOutputHandler();
		$company = new \Beech\WorkFlow\Tests\Functional\Fixtures\Domain\Model\Company();
		$company->setTitle('foo');
		$outputHandler->setEntity($company);
		$action->addOutputHandler($outputHandler);

		$this->actionRepository->add($action);

		$this->documentManager->flush();

		$persistedActions = $this->actionRepository->findAll();
		$this->assertEquals($action, $persistedActions[0]);

			// Make sure no company was added already
		$this->assertEquals(0, $this->companyRepository->countAll());

		$persistedActions[0]->dispatch();
		$this->persistenceManager->persistAll();

			// Test if a company entity was added
		$this->assertEquals(2, $this->companyRepository->countAll());

			// Check the status of the action
		$persistedActions = $this->actionRepository->findAll();
		$this->assertEquals(\Beech\WorkFlow\Domain\Model\Action::STATUS_FINISHED, $persistedActions[0]->getStatus());
	}

	/**
	 * @test
	 */
	public function outputHandlersAreCorrectlyRetrievedFromTheDatabase() {
		$action = new Action();
		$entityOutputHandler = new \Beech\WorkFlow\OutputHandlers\EntityOutputHandler();
		$actionExpiredOutputHandler = new \Beech\WorkFlow\OutputHandlers\ActionExpiredOutputHandler();

		$action->addOutputHandler($entityOutputHandler);
		$action->addOutputHandler($actionExpiredOutputHandler);

		$this->actionRepository->add($action);
		$this->documentManager->flush();

		$this->assertEquals(1, $this->actionRepository->countAll());

		$persistedActions = $this->actionRepository->findAll();
		$outputHandlersOnPersistedAction = $persistedActions[0]->getOutputHandlers();

		$this->assertInstanceOf('Beech\WorkFlow\OutputHandlers\EntityOutputHandler', $outputHandlersOnPersistedAction[0]);
		$this->assertInstanceOf('Beech\WorkFlow\OutputHandlers\ActionExpiredOutputHandler', $outputHandlersOnPersistedAction[1]);
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

}

?>