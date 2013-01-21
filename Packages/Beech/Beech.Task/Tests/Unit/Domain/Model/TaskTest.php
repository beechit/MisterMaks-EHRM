<?php
namespace Beech\Ehrm\Tests\Unit\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 28-11-12 16:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Task\Domain\Model\Task;

/**
 * Testcase for Link
 */
class TaskTest extends \TYPO3\Flow\Tests\UnitTestCase {

	/**
	 * @test
	 */
	public function testInstance() {
		$task = new Task();
		$this->assertInstanceOf('Beech\Task\Domain\Model\Task', $task);
	}

	/**
	 * @test
	 */
	public function getterSetterTest() {
		$task = new Task();
		$person = new \Beech\Party\Domain\Model\Person();
		$time = new \DateTime();

		$mockPersistenceManager = $this->getMock('TYPO3\Flow\Persistence\Doctrine\PersistenceManager', array(), array(), '', FALSE);
		$mockPersistenceManager->expects($this->any())->method('getIdentifierByObject')->will($this->returnValue('abc123'));
		$mockPersistenceManager->expects($this->any())->method('getObjectByIdentifier')->will($this->returnValue($person));

		$this->inject($task, 'persistenceManager', $mockPersistenceManager);

		$task->setCreatedBy($person);
		$task->setAssignedTo($person);

		$task->setCloseDateTime($time);
		$task->setCreationDateTime($time);

		$task->setDescription('description');
		$task->setPriority(Task::PRIORITY_HIGH);

		$this->assertEquals($person, $task->getCreatedBy());
		$this->assertEquals($person, $task->getAssignedTo());

		$this->assertEquals($time, $task->getCloseDateTime());
		$this->assertEquals($time, $task->getCreationDateTime());

		$this->assertEquals('description', $task->getDescription());
		$this->assertEquals(Task::PRIORITY_HIGH, $task->getPriority());
	}

	/**
	 * @test
	 */
	public function assignedToIdentifierConversionWorksAsExpected() {
		$task = new Task();

		$john = new \Beech\Party\Domain\Model\Person();
		$john->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'John', '', 'Doe'));
		$jane = new \Beech\Party\Domain\Model\Person();
		$jane->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Jane', '', 'Doe'));

		$mockPersistenceManager = $this->getMock('TYPO3\Flow\Persistence\Doctrine\PersistenceManager', array(), array(), '', FALSE);

		$mockPersistenceManager->expects($this->any())->method('getIdentifierByObject')->with($jane)->will($this->returnValue('abcjane'));
		$mockPersistenceManager->expects($this->any())->method('getObjectByIdentifier')->with('abcjane')->will($this->returnValue($jane));

		$this->inject($task, 'persistenceManager', $mockPersistenceManager);

		$task->setAssignedTo($jane);

		$this->assertEquals($jane, $task->getAssignedTo());
		$this->assertNotEquals($john, $task->getAssignedTo());
	}

	/**
	 * @test
	 */
	public function createdByIdentifierConversionWorksAsExpected() {
		$task = new Task();

		$john = new \Beech\Party\Domain\Model\Person();
		$john->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'John', '', 'Doe'));
		$jane = new \Beech\Party\Domain\Model\Person();
		$jane->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Jane', '', 'Doe'));

		$mockPersistenceManager = $this->getMock('TYPO3\Flow\Persistence\Doctrine\PersistenceManager', array(), array(), '', FALSE);

		$mockPersistenceManager->expects($this->any())->method('getIdentifierByObject')->with($jane)->will($this->returnValue('abcjane'));
		$mockPersistenceManager->expects($this->any())->method('getObjectByIdentifier')->with('abcjane')->will($this->returnValue($jane));

		$this->inject($task, 'persistenceManager', $mockPersistenceManager);

		$task->setCreatedBy($jane);

		$this->assertEquals($jane, $task->getCreatedBy());
		$this->assertNotEquals($john, $task->getCreatedBy());
	}

}

?>