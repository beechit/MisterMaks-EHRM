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

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $party;

	/**
	 * @var \Beech\Absence\Domain\Model\AbsenceArrangement
	 * @ODM\Field(type="mixed")
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
	protected $personSubject;

	/**
	 * The person initiating this absence Registration
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $personInitiator;

	/**
	 * The the report method that the subject used
	 * @var string
	 * @Flow\Validate(type="NotEmpty", validationGroups={"Controller"})
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $reportMethod;

	/**
	 * The remark on this absence
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $remark;

	/**
	 * The requestStatus on this absence
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $requestStatus;

	/**
	 * The needsGrant on this absence
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $needsGrant;

	/**
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 * @ODM\Index
	 */
	protected $startDateTime;

	/**
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 * @ODM\Index
	 */
	protected $endDateTime;


	/**
	 * Set the person that is subject of the absence
	 *
	 * @param \Beech\Party\Domain\Model\Person $personSubject
	 * @return void
	 */
	public function setPersonSubject(\Beech\Party\Domain\Model\Person $personSubject) {
		$this->personSubject = $this->persistenceManager->getIdentifierByObject($personSubject, '\Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns the person that is subject of the absence
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPersonSubject() {
		if (isset($this->personSubject)) {
			return $this->persistenceManager->getObjectByIdentifier($this->personSubject, '\Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * Set the person who initiated this absenceRegistration.
	 * Load the current user if NULL was emitted
	 *
	 * @param \Beech\Party\Domain\Model\Person $personInitiator
	 * @return void
	 */
	public function setPersonInitiator(\Beech\Party\Domain\Model\Person $personInitiator = NULL) {
		if ($personInitiator === NULL ) {
			if (is_object($this->securityContext->getAccount())
				&& $this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person) {
				$personInitiator = $this->securityContext->getAccount()->getParty();
			}
		}
		$this->personInitiator = $this->persistenceManager->getIdentifierByObject($personInitiator, '\Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns the person who initiated this absenceregistration.
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPersonInitiator() {
		if (isset($this->personInitiator)) {
			return $this->persistenceManager->getObjectByIdentifier($this->personInitiator, '\Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * @param \DateTime $startDateTime
	 * @return void
	 */
	public function setStartDateTime(\DateTime $startDateTime = NULL) {
		if ($startDateTime === NULL) {
			$startDateTime = new \DateTime();
		}
		$this->startDateTime = $startDateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getStartDateTime() {
		return $this->startDateTime;
	}

	/**
	 * @param \DateTime $endDateTime
	 * @return void
	 */
	public function setEndDateTime(\DateTime $endDateTime = NULL) {
		if ($endDateTime === NULL) {
			$endDateTime = new \DateTime();
		}
		$this->endDateTime = $endDateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndDateTime() {
		return $this->EndDateTime;
	}
}
?>