<?php
namespace Beech\Calendar\Tests\Functional\Domain\Model;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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