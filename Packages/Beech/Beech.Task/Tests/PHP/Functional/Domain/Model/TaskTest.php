<?php
namespace Beech\Ehrm\Tests\Functional\Domain\Model;

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

		$person = new \Beech\Party\Domain\Model\Person();
		$person->setName(new \Beech\Party\Domain\Model\PersonName(NULL, 'John', NULL, 'Doe'));

		$this->personRepository->add($person);
		$this->persistenceManager->persistAll();

		$account = new \TYPO3\Flow\Security\Account();
		$account->setParty($person);

		$this->authenticateAccount($account);
	}

	public function tearDown() {
		parent::tearDown();
	}

	/**
	 * @test
	 */
	public function testPersonIsPersistedCorrectly() {
		$this->assertEquals(1, $this->personRepository->countAll());
	}

	/**
	 * @test
	 */
	public function testIfCreatedByIsSetCorrectly() {
		$task = new \Beech\Task\Domain\Model\Task();
		$this->assertEquals('John Doe', $task->getCreatedBy()->getName()->getFullName());
	}

	/**
	 * @test
	 */
	public function countByAssignedToReturnsTheCorrectNumberOfTasks() {
		$john = new \Beech\Party\Domain\Model\Person();
		$john->setName(new \Beech\Party\Domain\Model\PersonName(NULL, 'John', NULL, 'Doe'));
		$jane = new \Beech\Party\Domain\Model\Person();
		$jane->setName(new \Beech\Party\Domain\Model\PersonName(NULL, 'Jane', NULL, 'Doe'));

		$this->personRepository->add($john);
		$this->personRepository->add($jane);

		$this->persistenceManager->persistAll();

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
		$john = new \Beech\Party\Domain\Model\Person();
		$john->setName(new \Beech\Party\Domain\Model\PersonName(NULL, 'John', NULL, 'Doe'));

		$this->personRepository->add($john);

		$this->persistenceManager->persistAll();

		for ($i = 0; $i < 10; $i ++) {
			$task = new \Beech\Task\Domain\Model\Task();
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

}

?>