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
 * The extension of Person in CouchDB
 *
 * @ODM\Document(indexed=true)
 */
class PersonCouchDocument extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * @var \Beech\Ehrm\Domain\Model\PersonPreferences
	 * @ODM\ReferenceOne(targetDocument="\Beech\Ehrm\Domain\Model\PersonPreferences", cascade={"persist"})
	 */
	protected $preferences;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * Set preferences
	 *
	 * @param \Beech\Ehrm\Domain\Model\PersonPreferences $preferences
	 */
	public function setPreferences(\Beech\Ehrm\Domain\Model\PersonPreferences $preferences) {
		$this->preferences = $preferences;
	}

	/**
	 * Get preferences
	 *
	 * @return \Beech\Ehrm\Domain\Model\PersonPreferences
	 */
	public function getPreferences() {
		if ($this->preferences === NULL) {
			$this->preferences = new \Beech\Ehrm\Domain\Model\PersonPreferences();
		}
		return $this->preferences;
	}

}

?>