<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 25-07-12 16:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Education
 *
 * @ODM\Document(indexed=true)
 */
class PersonRelation extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $person;

	/**
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $personRelatedTo;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * Sets person
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function setPerson(\Beech\Party\Domain\Model\Person $person) {
		$this->person = $this->persistenceManager->getIdentifierByObject($person, 'Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns a person
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPerson() {
		if (isset($this->person)) {
			return $this->persistenceManager->getObjectByIdentifier($this->person, 'Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * Sets personRelatedTo who is in relation with person
	 *
	 * @param \Beech\Party\Domain\Model\Person $personRelatedTo
	 * @return void
	 */
	public function setPersonRelatedTo(\Beech\Party\Domain\Model\Person $personRelatedTo) {
		$this->personRelatedTo = $this->persistenceManager->getIdentifierByObject($personRelatedTo, 'Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns the owner for this task
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getPersonRelatedTo() {
		if (isset($this->personRelatedTo)) {
			return $this->persistenceManager->getObjectByIdentifier($this->personRelatedTo, 'Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}
}

?>