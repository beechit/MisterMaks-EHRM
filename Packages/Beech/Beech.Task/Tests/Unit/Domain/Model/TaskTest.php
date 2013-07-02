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
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 */
	protected $configurationManager;

	protected $persons = array();

	public function setUp() {
		parent::setUp();
		$this->configurationManager = new \TYPO3\Flow\Configuration\ConfigurationManager(new \TYPO3\Flow\Core\ApplicationContext('Testing'));
		$this->inject($this->configurationManager, 'configurationSource', new \TYPO3\Flow\Configuration\Source\YamlSource());
		$this->configurationManager->registerConfigurationType('Models', \TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT, TRUE);
	}

	/**
	 * @test
	 */
	public function testInstance() {
		$task = new Task();
		$this->inject($task, 'configurationManager', $this->configurationManager);
		$this->assertInstanceOf('Beech\Task\Domain\Model\Task', $task);
	}

	/**
	 * @test
	 */
	public function getterSetterTest() {
		$task = new Task();
		$person = new \Beech\Party\Domain\Model\Person();

		$time = new \DateTime();
		$endTime = new \DateTime('+1week');
		$link = new \Beech\Ehrm\Domain\Model\Link();
		$priority = \Beech\Task\Domain\Model\Task::PRIORITY_NORMAL;

		$action = new \Beech\Workflow\Domain\Model\Action();

		$mockPersistenceManager = $this->getMock('TYPO3\Flow\Persistence\Doctrine\PersistenceManager', array(), array(), '', FALSE);
		$mockPersistenceManager->expects($this->any())->method('getIdentifierByObject')->will($this->returnValue('abc123'));
		$mockPersistenceManager->expects($this->any())->method('getObjectByIdentifier')->will($this->returnValue($person));

		$this->inject($task, 'persistenceManager', $mockPersistenceManager);
		$this->inject($task, 'configurationManager', $this->configurationManager);

		$task->setCreatedBy($person);
		$task->setAssignedTo($person);

		$task->setCloseDateTime($time);
		$task->setCreationDateTime($time);
		$task->setEndDateTime($endTime);

		$task->setDescription('description');
		$task->setLink($link);
		$task->setPriority($priority);
		$task->setAction($action);

		$this->assertEquals($person, $task->getCreatedBy());
		$this->assertEquals($person, $task->getAssignedTo());

		$this->assertEquals($time, $task->getCloseDateTime());
		$this->assertEquals($time, $task->getCreationDateTime());
		$this->assertEquals($endTime, $task->getEndDateTime());

		$this->assertEquals('description', $task->getDescription());
		$this->assertEquals($link, $task->getLink());
		$this->assertEquals($priority, $task->getPriority());
		$this->assertEquals($action, $task->getAction());
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
		$this->inject($task, 'configurationManager', $this->configurationManager);

		$task->setAssignedTo($jane);

		$this->assertEquals($jane, $task->getAssignedTo());
		$this->assertNotEquals($john, $task->getAssignedTo());
	}

	/**
	 * @test
	 */
	public function calculateEscalationDateWorksAsExpected() {
		$task = new Task();
		$creationDate = new \DateTime();
		$endDate = new \DateTime('1week');
		$interval = '1day';

		$this->inject($task, 'configurationManager', $this->configurationManager);

		$escalationDate1 = clone $creationDate;
		$escalationDate1->add(\DateInterval::createFromDateString($interval));
		$escalationDate2 = clone $endDate;
		$escalationDate2->sub(\DateInterval::createFromDateString($interval));

		$task->setCreationDateTime($creationDate);
			// no escalationInterval results in NULL
		$this->assertEmpty($task->getEscalationDateTime());

		$task->setEscalationInterval($interval);
			// only escalationInterval then date is calculated from creationdate
		$this->assertEquals($escalationDate1, $task->getEscalationDateTime());

		$task->setEndDateTime($endDate);
			// endDateTime set then date is calculated from that datetime
		$this->assertEquals($escalationDate2, $task->getEscalationDateTime());
	}

	/**
	 * @test
	 */
	public function calculateIncreasePriorityWorksAsExpected() {
		$task = new Task();
		$this->inject($task, 'configurationManager', $this->configurationManager);
		$task->setPriority(Task::PRIORITY_NORMAL);
		$creationDate = new \DateTime();
		$endDate = new \DateTime('1month');
		$interval = '1day';


		$date1 = clone $creationDate;
		$date1->add(\DateInterval::createFromDateString($interval));
		$date2 = clone $endDate;
		$date2->sub(\DateInterval::createFromDateString($interval));

		$task->setCreationDateTime($creationDate);

			// no increasePriorityInterval results in NULL
		$this->assertEmpty($task->getNextPriorityIncreaseDateTime());

		$task->setIncreasePriorityInterval($interval);
			// only increasePriorityInterval then date is calculated from creationdate
		$this->assertEquals($date1, $task->getNextPriorityIncreaseDateTime());

		$task->setEndDateTime($endDate);

			// endDateTime set then date is calculated from that datetime
		$this->assertEquals($date2, $task->getNextPriorityIncreaseDateTime());

			// loser priority then it needs on other interval
		$task->setPriority(Task::PRIORITY_LOW);
		$date2->sub(\DateInterval::createFromDateString($interval));
		$this->assertEquals($date2, $task->getNextPriorityIncreaseDateTime());
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
		$this->inject($task, 'configurationManager', $this->configurationManager);

		$task->setCreatedBy($jane);

		$this->assertEquals($jane, $task->getCreatedBy());
		$this->assertNotEquals($john, $task->getCreatedBy());
	}

	/**
	 * Create personRepository Mock with 2 persons
	 *
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	public function setupPersonRepository() {
		$couchDocumentMock = $this->getMock('Beech\Ehrm\Domain\Model\Document');

		$jane = new \Beech\Party\Domain\Model\Person();
		$john = new \Beech\Party\Domain\Model\Person();

		$this->inject($jane, 'documentObject', $couchDocumentMock);
		$this->inject($john, 'documentObject', $couchDocumentMock);
		$john->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'John', '', 'Doe'));
		$jane->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Jane', '', 'Doe'));
		$this->persons['abcjane'] = $jane;
		$this->persons['abcjohn'] = $john;

		$mockPersistenceManager = $this->getMock('TYPO3\Flow\Persistence\Doctrine\PersistenceManager', array(), array(), '', FALSE);

		$mockPersistenceManager->expects($this->any())->method('getIdentifierByObject')->with($this->logicalOr(
			$this->equalTo($jane),
			$this->equalTo($john)
		), 'Beech\Party\Domain\Model\Person')->will($this->returnCallback(array($this,'getIdentifierByObjectCallback')));

		$mockPersistenceManager->expects($this->any())->method('getObjectByIdentifier')->with($this->logicalOr(
			$this->equalTo('abcjane'),
			$this->equalTo('abcjohn')
		), 'Beech\Party\Domain\Model\Person')->will($this->returnCallback(array($this,'getObjectByIdentifierCallback')));

		return $mockPersistenceManager;
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return string
	 */
	public function getIdentifierByObjectCallback($person) {
		return array_search($person, $this->persons);
	}

	/**
	 * @param string $personId
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getObjectByIdentifierCallback($personId) {
		return $this->persons[$personId];
	}

	/**
	 * @test
	 */
	public function escalationWorksAsExpected() {
		$task = new Task();

		$this->inject($task, 'persistenceManager', $this->setupPersonRepository());
		$this->inject($task, 'configurationManager', $this->configurationManager);

		$jane = $this->persons['abcjane'];
		$john = $this->persons['abcjohn'];

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