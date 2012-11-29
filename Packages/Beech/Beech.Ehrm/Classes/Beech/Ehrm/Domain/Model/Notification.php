<?php
namespace Beech\Ehrm\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 04-09-12 12:25
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Notification
 *
 * @ODM\Document(indexed=true)
 */
class Notification {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * The label
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $label;

	/**
	 * The party
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="string")
	 */
	protected $party;

	/**
	 * The closeable
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 */
	protected $closeable;

	/**
	 * The sticky
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 */
	protected $sticky;

	/**
	 * Get the Notification's label
	 *
	 * @return string The Notification's label
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * Sets this Notification's label
	 *
	 * @param string $label The Notification's label
	 * @return void
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * Get the Notification's party
	 *
	 * @return \TYPO3\Party\Domain\Model\AbstractParty The Notification's partu
	 */
	public function getParty() {
		if (isset($this->party)) {
			return $this->persistenceManager->getObjectByIdentifier($this->party);
		}
		return NULL;
	}

	/**
	 * Sets this Notification's party
	 *
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $party The Notification's party
	 * @return void
	 */
	public function setParty(\TYPO3\Party\Domain\Model\AbstractParty $party) {
		$this->party = $this->persistenceManager->getIdentifierByObject($party);
	}

	/**
	 * Get the Notification's closeable
	 *
	 * @return boolean The Notification's closeable
	 */
	public function getCloseable() {
		return $this->closeable;
	}

	/**
	 * Sets this Notification's closeable
	 *
	 * @param boolean $closeable The Notification's closeable
	 * @return void
	 */
	public function setCloseable($closeable) {
		$this->closeable = $closeable;
	}

	/**
	 * Get the Notification's sticky
	 *
	 * @return boolean The Notification's sticky
	 */
	public function getSticky() {
		return $this->sticky;
	}

	/**
	 * Sets this Notification's sticky
	 *
	 * @param boolean $sticky The Notification's sticky
	 * @return void
	 */
	public function setSticky($sticky) {
		$this->sticky = $sticky;
	}

}

?>