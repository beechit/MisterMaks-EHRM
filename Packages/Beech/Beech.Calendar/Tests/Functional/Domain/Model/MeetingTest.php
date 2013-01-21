<?php
namespace Beech\Calendar\Tests\Functional\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15-10-12 17:23
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Calendar\Domain\Model\Meeting as Meeting;

/**
 * Persistence test for Meeting entity
 */
class MeetingTest extends \Radmiraal\CouchDB\Tests\Functional\AbstractFunctionalTest {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \Beech\Calendar\Domain\Repository\MeetingRepository
	 */
	protected $meetingRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 */
	public function setUp() {
		parent::setUp();
		$this->meetingRepository = $this->objectManager->get('Beech\Calendar\Domain\Repository\MeetingRepository');
		$this->personRepository = $this->objectManager->get('Beech\Party\Domain\Repository\PersonRepository');
	}

	/**
	 * Simple test for meeting persistence
	 *
	 * @test
	 */
	public function meetingCanBePersistedAndRetrieved() {
		$personOne = new \Beech\Party\Domain\Model\Person();
		$personOne->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Bram', '', 'Verhaegh'));
		$this->personRepository->add($personOne);

		$personTwo = new \Beech\Party\Domain\Model\Person();
		$personTwo->setName(new \TYPO3\Party\Domain\Model\PersonName('', 'Edward', '', 'Lenssen'));
		$this->personRepository->add($personTwo);

		$this->persistenceManager->persistAll();

		$meeting = new Meeting();
		$meeting->setSubject('Title of this meeting');
		$meeting->setDescription('Description of this meeting');
		$meeting->setStartDateTime(new \DateTime('2012-01-01 00:00:01'));
		$meeting->setEndDateTime(new \DateTime('2012-01-01 00:12:01'));
		$meeting->addAttendee($personOne);
		$meeting->addAttendee($personTwo);
		$this->meetingRepository->add($meeting);

		$this->documentManager->flush();

		$persistedMeetings = $this->meetingRepository->findAll();

		$this->assertEquals($meeting, $persistedMeetings[0]);
		$attendees = $persistedMeetings[0]->getAttendees();
		$this->assertEquals(2, count($attendees));

		$this->assertEquals('Bram Verhaegh', $attendees[0]->getName()->getFullName());
		$this->assertEquals('Edward Lenssen', $attendees[1]->getName()->getFullName());
	}
}
?>