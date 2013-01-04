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
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 * @var \Beech\Task\Domain\Repository\TaskRepository
	 */
	protected $taskRepository;

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
		$person->setName(new \TYPO3\Party\Domain\Model\PersonName('', $firstName, '', $lastName));

		$this->personRepository->add($person);
		$this->persistenceManager->persistAll();

		if ($authenticate === TRUE) {
			$account = new \TYPO3\Flow\Security\Account();
			$account->setParty($person);

			$this->authenticateAccount($account);
		}

		return $person;
	}

	/**
	 * @test
	 */
	public function testPersonIsPersistedCorrectly() {
		$this->createPerson('John', 'Doe');
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
		$john = $this->createPerson('John', 'Doe');
		$jane = $this->createPerson('Jane', 'Doe');

		for ($i = 0; $i < 10; $i ++) {
			$task = new \Beech\Task\Domain\Model\Task();
			$task->setAssignedTo($john);
			$task->setDescription('Task ' . $i);
			$this->taskRepository->add($task);
		}

		for ($i = 10; $i < 15; $i ++) {
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

		for ($i = 0; $i < 10; $i ++) {
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

		$this->markTestSkipped('Simple value storage works, TODO: make objects persisted correctly');
		$this->assertEquals('Test', $lastPersistedTask->getLink()->getControllerName());
	}

	/**
	 * @test
	 * @expectedException \Beech\Task\Exception
	 */
	public function taskCanNotBeClosedByPartyThatNotCreatedTheTask() {
		$john = $this->createPerson('John', 'Doe');
		$this->createPerson('Jane', 'Doe', TRUE);

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
		$john = $this->createPerson('John', 'Doe');
		$jane = $this->createPerson('Jane', 'Doe', TRUE);

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

}

?>