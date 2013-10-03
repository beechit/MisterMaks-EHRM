<?php
namespace Beech\Task\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 28-11-12 16:39
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Task\Domain\Model\Task;

/**
 * Testcase for Link
 */
class TaskTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var \TYPO3\Party\Domain\Repository\PartyRepository
	 */
	protected $personRepository;

	/**
	 * @var \Beech\Task\Domain\Repository\TaskRepository
	 */
	protected $taskRepository;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var boolean
	 */
	protected $testableSecurityEnabled = TRUE;

	public function setUp() {
		parent::setUp();

		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
		$this->taskRepository = $this->objectManager->get('Beech\Task\Domain\Repository\TaskRepository');
		$this->taskRepository->injectDocumentManagerFactory($this->documentManagerFactory);
		$this->inject($this->taskRepository, 'reflectionService', $this->objectManager->get('TYPO3\Flow\Reflection\ReflectionService'));
		$this->inject($this->taskRepository, 'persistenceManager', $this->persistenceManager);
	}

	/**
	 * Authenticates a person with given name
	 *
	 * @param string $firstName
	 * @param string $lastName
	 * @param boolean $authenticate
	 * @return \Beech\Party\Domain\Model\Person
	 */
	protected function createPerson($firstName, $lastName, $authenticate = FALSE) {
		$person = new \Beech\Party\Domain\Model\Person();
		$name = new \TYPO3\Party\Domain\Model\PersonName('', $firstName, '', $lastName);
		$person->setName($name);

		// set account before persisting so aspects that rely on the account don't break
		if ($authenticate === TRUE) {
			$account = new \TYPO3\Flow\Security\Account();
			$account->setParty($person);

			$this->authenticateAccount($account);
		}

		$this->personRepository->add($person);
		$this->persistenceManager->persistAll();

		if ($authenticate === TRUE) {
			$person->addAccount($account);
			$this->persistenceManager->persistAll();
		}

		return $person;
	}

	/**
	 * @test
	 */
	public function testPersonIsPersistedCorrectly() {
		$this->createPerson('John', 'Doe', TRUE);
		$this->assertEquals(1, $this->personRepository->countAll());
	}

	/**
	 * @test
	 */
	public function testIfCreatedByIsSetCorrectly() {
		$this->createPerson('John', 'Doe', TRUE);
		$task = new \Beech\Task\Domain\Model\Task();
		$this->assertEquals('John Doe', $task->getCreatedBy()->getName()->getFullName());
	}

	/**
	 * @test
	 */
	public function countByAssignedToReturnsTheCorrectNumberOfTasks() {

		$this->createPerson('Jan', 'Janssen', TRUE);

		$john = $this->createPerson('John', 'Doe');
		$jane = $this->createPerson('Jane', 'Doe');

		for ($i = 0; $i < 10; $i++) {
			$task = new \Beech\Task\Domain\Model\Task();
			$task->setAssignedTo($john);
			$task->setDescription('Task ' . $i);
			$this->taskRepository->add($task);
		}

		for ($i = 10; $i < 15; $i++) {
			$task = new \Beech\Task\Domain\Model\Task();
			$task->setAssignedTo($jane);
			$task->setDescription('Task ' . $i);
			$this->taskRepository->add($task);
		}

		$this->documentManager->flush();

		$this->assertEquals(15, count($this->taskRepository->findAll()));

		$this->assertEquals(10, $this->taskRepository->countByAssignedTo($john));
		$this->assertEquals(5, $this->taskRepository->countByAssignedTo($jane));
	}

	/**
	 * @test
	 */
	public function countOpenTasksByPersonReturnsTheCorrectNumberOfTasks() {
		$john = $this->createPerson('John', 'Doe', TRUE);

		for ($i = 0; $i < 10; $i++) {
			$task = new \Beech\Task\Domain\Model\Task();
			$task->setCloseableByAssignee(TRUE);
			$task->setAssignedTo($john);
			$task->setDescription('Task ' . $i);
			if ($i > 4) {
				$task->close();
			}
			$this->taskRepository->add($task);
		}

		$this->documentManager->flush();

		$this->assertEquals(10, count($this->taskRepository->findAll()));

		$this->assertEquals(10, $this->taskRepository->countByAssignedTo($john));
		$this->assertEquals(5, $this->taskRepository->countOpenTasksByPerson($john));
	}

	/**
	 * @test
	 */
	public function testIfALinkCanBePersistedOnATaskDocument() {
		$link = new \Beech\Ehrm\Domain\Model\Link();
		$link->setControllerName('Test');

		$task = new \Beech\Task\Domain\Model\Task();
		$task->setDescription('Link Test');
		$task->setLink($link);

		$this->taskRepository->add($task);
		$this->documentManager->flush();
		$this->documentManager->clear();

		$tasks = $this->taskRepository->findAll();
		$this->assertEquals(1, count($tasks));
		$lastPersistedTask = $tasks[count($tasks) - 1];

		$this->assertEquals('Test', $lastPersistedTask->getLink()->getControllerName());
	}

	/**
	 * @test
	 * @expectedException \Beech\Task\Exception
	 */
	public function taskCanNotBeClosedByPartyThatNotCreatedTheTask() {
		$this->createPerson('Jane', 'Doe', TRUE);
		$john = $this->createPerson('John', 'Doe');

		$task = new \Beech\Task\Domain\Model\Task();
		$task->setCreatedBy($john);

		$task->close();
	}

	/**
	 * @test
	 */
	public function taskCanBeClosedByPartyThatCreatedTheTask() {
		$jane = $this->createPerson('Jane', 'Doe', TRUE);

		$task = new \Beech\Task\Domain\Model\Task();
		$task->setCreatedBy($jane);

		$task->close();

		$this->assertTrue($task->isClosed());
	}

	/**
	 * @test
	 * @expectedException \Beech\Task\Exception
	 */
	public function taskCanNotBeClosedByAssigneeIfTaskIsNotCloseableByAssignee() {
		$jane = $this->createPerson('Jane', 'Doe', TRUE);
		$john = $this->createPerson('John', 'Doe');

		$task = new \Beech\Task\Domain\Model\Task();
		$task->setCreatedBy($john);
		$task->setCloseableByAssignee(FALSE);
		$task->setAssignedTo($jane);

		$task->close();
	}

	/**
	 * @test
	 */
	public function taskCanBeClosedByAssigneeIfTaskIsCloseableByAssignee() {
		$jane = $this->createPerson('Jane', 'Doe', TRUE);

		$task = new \Beech\Task\Domain\Model\Task();
		$task->setCloseableByAssignee(TRUE);
		$task->setAssignedTo($jane);

		$task->close();

		$this->assertTrue($task->isClosed());
	}


	/**
	 * @test
	 */
	public function escalationWorksAsExpected() {
		$task = new Task();

		$jane = $this->createPerson('Jane', 'Doe', TRUE);
		$john = $this->createPerson('John', 'Doe', TRUE);

		// no one assign so can't escalate
		$this->assertFalse($task->escalate());

		// someone assigne but had no manager
		$task->setAssignedTo($jane);
		$this->assertFalse($task->escalate());

		// assign person to a manager
		// @todo: manager part has to be implemented for now createdBy
		$this->markTestSkipped('person->getManager() needs to get implemented and/or person->$createdBy needs to get moved to model instait of YAML because can not mock it now');

		$jane->setCreatedBy($john);
		$this->assertTrue($task->escalate());
	}
}

?>