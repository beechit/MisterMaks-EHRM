<?php
namespace Beech\Calendar\Domain\Model;

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

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Meeting
 *
 * @ODM\Document(indexed=true)
 */
class Meeting extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * The subject
	 *
	 * @var string
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 * @ODM\Field(type="string")
	 */
	protected $subject;

	/**
	 * The description
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $description;

	/**
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 */
	protected $startDateTime;

	/**
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 */
	protected $endDateTime;

	/**
	 * The attendees for this Meeting
	 *
	 * @var \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Person>
	 * @ODM\Field(type="mixed")
	 */
	protected $attendees = array();

	/**
	 * Constructs the Meeting
	 */
	public function __construct() {
		$this->attendees = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Set subject
	 *
	 * @param string $subject
	 * @return void
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * Returns the subject
	 *
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Returns the description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param \DateTime $startDateTime
	 * @return void
	 */
	public function setStartDateTime(\DateTime $startDateTime) {
		$this->startDateTime = $startDateTime;
	}

	/**
	 * @return \DateTime
	 * @return void
	 */
	public function getStartDateTime() {
		return $this->startDateTime;
	}

	/**
	 * @param \DateTime $endDateTime
	 */
	public function setEndDateTime(\DateTime $endDateTime) {
		$this->endDateTime = $endDateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndDateTime() {
		return $this->endDateTime;
	}

	/**
	 * Adds an attendee to this meeting
	 *
	 * @param \Beech\Party\Domain\Model\Person $attendee
	 * @return void
	 */
	public function addAttendee(\Beech\Party\Domain\Model\Person $attendee) {
		$this->attendees->add(
			$this->persistenceManager->getIdentifierByObject($attendee)
		);
	}

	/**
	 * Removes an attendee from this meeting
	 *
	 * @param \Beech\Party\Domain\Model\Person $attendee
	 * @return void
	 */
	public function removeAttendee(\Beech\Party\Domain\Model\Person $attendee) {
		$this->attendees->removeElement(
			$this->persistenceManager->getIdentifierByObject($attendee)
		);
	}

	/**
	 * Returns all known addresses of this company.
	 *
	 * @return \Doctrine\Common\Collections\Collection<\Beech\Party\Domain\Model\Person>
	 */
	public function getAttendees() {
		$returnValue = new \Doctrine\Common\Collections\ArrayCollection();
		if (!is_null($this->attendees)) {
			foreach ($this->attendees as $attendee) {
				$_attendee = $this->persistenceManager->getObjectByIdentifier($attendee, 'Beech\Party\Domain\Model\Person');
				if($_attendee !== NULL) {
					$returnValue->add($_attendee);
				}
			}
		}
		return $returnValue;
	}

}

?>