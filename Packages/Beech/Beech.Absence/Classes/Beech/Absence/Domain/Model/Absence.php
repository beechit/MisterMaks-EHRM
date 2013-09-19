<?php
namespace Beech\Absence\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Absence
 *
 * @ODM\Document(indexed=true)
 */
class Absence extends \Beech\Ehrm\Domain\Model\Document {

	const OPTION_LEAVE = 'leave';
	const OPTION_SICKNESS = 'sickness';

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * @var \Beech\Party\Domain\Model\Company
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $department;

	/**
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $absenceType;

	/**
	 * @var \Beech\Absence\Domain\Model\AbsenceArrangement
	 * @ODM\ReferenceOne(targetDocument="\Beech\Absence\Domain\Model\AbsenceArrangement")
	 * @ODM\Index
	 */
	protected $absenceArrangement;

	/**
	 * The person that is subject of this absence
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @Flow\Validate(type="NotEmpty")
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $person;

	/**
	 * The person initiating this absence Registration
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $reportedTo;

	/**
	 * The the report method that the subject used
	 * @var string
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */

	/**
	 * @var \DateTime
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 * @ODM\Field(type="datetime")
	 * @ODM\Index
	 */
	protected $startDate;

	/**
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 * @ODM\Index
	 */
	protected $endDate;

	/**
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 * @ODM\Index
	 */
	protected $estimatedRecoveryDate;


	/**
	 * Set the person that is subject of the absence
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function setPerson(\Beech\Party\Domain\Model\Person $person) {
		$this->person = $this->persistenceManager->getIdentifierByObject($person, '\Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns the person that is subject of the absence
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPerson() {
		if (isset($this->person)) {
			return $this->persistenceManager->getObjectByIdentifier($this->person, '\Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * Set the person who initiated this absenceRegistration.
	 * Load the current user if NULL was emitted
	 *
	 * @param \Beech\Party\Domain\Model\Person $reportedTo
	 * @return void
	 */
	public function setReportedTo(\Beech\Party\Domain\Model\Person $reportedTo = NULL) {
		if ($reportedTo === NULL ) {
			if (is_object($this->securityContext->getAccount())
				&& $this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person) {
				$reportedTo = $this->securityContext->getAccount()->getParty();
			}
		}
		$this->reportedTo = $this->persistenceManager->getIdentifierByObject($reportedTo, '\Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns the person who initiated this absenceregistration.
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getReportedTo() {
		if (isset($this->reportedTo)) {
			return $this->persistenceManager->getObjectByIdentifier($this->reportedTo, '\Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * Set the absenceArrangement that is subject of the absence
	 *
	 * @param \Beech\Absence\Domain\Model\AbsenceArrangement $absenceArrangement
	 * @return void
	 */
	public function setAbsenceArrangement(\Beech\Absence\Domain\Model\AbsenceArrangement $absenceArrangement) {
		$this->absenceArrangement = $absenceArrangement;
	}

	/**
	 * Returns the absenceArrangement that is subject of the absence
	 *
	 * @return \Beech\Absence\Domain\Model\AbsenceArrangement
	 */
	public function getAbsenceArrangement() {
		return $this->absenceArrangement;
	}

	/**
	 * @param \DateTime $startDateTime
	 * @return void
	 */
	public function setStartDate(\DateTime $startDate = NULL) {
		if ($startDate === NULL) {
			$startDate = new \DateTime();
		}
		$this->startDate = $startDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getStartDate() {
		return $this->startDate;
	}

	/**
	 * @param \DateTime $endDate
	 * @return void
	 */
	public function setEndDate(\DateTime $endDate = NULL) {
		if ($endDate === NULL) {
			$endDate = new \DateTime();
		}
		$this->endDate = $endDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndDate() {
		return $this->endDate;
	}

	/**
	 * @param \DateTime $estimatedRecoveryDate
	 * @return void
	 */
	public function setEstimatedRecoveryDate(\DateTime $estimatedRecoveryDate = NULL) {
		if ($estimatedRecoveryDate === NULL) {
			$estimatedRecoveryDate = new \DateTime();
		}
		$this->estimatedRecoveryDate = $estimatedRecoveryDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getEstimatedRecoveryDate() {
		return $this->estimatedRecoveryDate;
	}

	/**
	 * Calculated value for number of days when absence is
	 *
	 * @return integer
	 */
	public function getDays() {
		// TODO: Calculate number of absence's days
		return 0;
	}

	/**
	 * Calculated value for number of days when absence is
	 * @return \DateTime
	 */
	public function getHours() {
		// TODO: Calculate number of absence's hours
		return 0;
	}
}
?>