<?php
namespace Beech\Calendar\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15-10-12
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Meeting
 *
 * @ODM\Document(indexed=true)
 */
class Meeting extends \Radmiraal\CouchDB\Persistence\AbstractDocument {

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
				$returnValue->add($this->persistenceManager->getObjectByIdentifier($attendee));
			}
		}
		return $returnValue;
	}

}

?>