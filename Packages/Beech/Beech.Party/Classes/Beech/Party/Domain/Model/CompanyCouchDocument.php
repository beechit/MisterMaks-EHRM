<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 03-07-2013 10:15
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * The extension of Company in CouchDB
 *
 * @ODM\Document(indexed=true)
 */
class CompanyCouchDocument extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @var \Beech\Party\Domain\Model\Person
	 * @ODM\Field(type="mixed")
	 * @ODM\Index
	 */
	protected $contactPerson;

	/**
	 * Set contactPerson
	 *
	 * @param \Beech\Party\Domain\Model\Person $contactPerson
	 */
	public function setContactPerson($contactPerson) {
		if ($contactPerson) {
			$this->contactPerson = $this->persistenceManager->getIdentifierByObject($contactPerson);
		} else {
			$this->contactPerson = NULL;
		}
	}

	/**
	 * Get contactPerson
	 *
	 * @return \Beech\Party\Domain\Model\Person
	 */
	public function getContactPerson() {
		if (!empty($this->contactPerson)) {
			return $this->persistenceManager->getObjectByIdentifier($this->contactPerson, '\Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

}

?>