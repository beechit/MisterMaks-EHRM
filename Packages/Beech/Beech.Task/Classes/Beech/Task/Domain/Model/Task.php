<?php
namespace Beech\Task\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-08-12 10:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Task Model
 *
 * @ODM\Document(indexed=true)
 */
class Task extends \Radmiraal\CouchDB\Persistence\AbstractDocument {

	const PRIORITY_LOW = 0;
	const PRIORITY_NORMAL = 1;
	const PRIORITY_HIGH = 2;
	const PRIORITY_VERY_HIGH = 3;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $persistenceManager;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 * @Flow\Transient
	 */
	protected $securityContext;

	/**
	 * The task description
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $description;

	/**
	 * The task owner
	 *
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="string")
	 * @ODM\Index
	 */
	protected $assignedTo;

	/**
	 * The task starter
	 * Property is nullable, because a task can be started by the system instead of a user
	 *
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="string")
	 */
	protected $createdBy;

	/**
	 * Priority of this task 0-4
	 *
	 * @var integer
	 * @Flow\Validate(type="NumberRange", options={ "minimum"=0, "maximum"=4 })
	 * @ODM\Field(type="integer")
	 * @ODM\Index
	 */
	protected $priority;

	/**
	 * The dateTime this task was created
	 *
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 */
	protected $creationDateTime;

	/**
	 * The datetime this task was closed
	 *
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 */
	protected $closeDateTime;

	/**
	 * @var boolean
	 * @ODM\Field(type="boolean")
	 * @ODM\Index
	 */
	protected $closed = FALSE;

	/**
	 * Initialize object
	 * @return void
	 */
	public function initializeObject() {
		$this->setCreatedBy();
	}

	/**
	 * Sets the task description
	 *
	 * @param string $description
	 * @return void
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Return the task description
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * Sets the owner for this task
	 *
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $assignee
	 * @return void
	 */
	public function setAssignedTo(\TYPO3\Party\Domain\Model\AbstractParty $assignee) {
		$this->assignedTo = $this->persistenceManager->getIdentifierByObject($assignee, 'Beech\Party\Domain\Model\Person');
	}

	/**
	 * Returns the owner for this task
	 *
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getAssignedTo() {
		if (isset($this->assignedTo)) {
			return $this->persistenceManager->getObjectByIdentifier($this->assignedTo, 'Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * Sets the starter of this task, load the current user if NULL was emitted
	 * We use the top-level AbstractParty class as typehint, because a task can also
	 * be started by a system process
	 *
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $creator
	 * @return void
	 */
	public function setCreatedBy(\TYPO3\Party\Domain\Model\AbstractParty $creator = NULL) {
		if ($creator === NULL ) {
			if (isset($this->securityContext) && $this->securityContext->isInitialized()
					&& $this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person) {
				$creator = $this->securityContext->getAccount()->getParty();
			}
		}

		if ($creator !== NULL) {
			$this->createdBy = $this->persistenceManager->getIdentifierByObject($creator, 'Beech\Party\Domain\Model\Person');
		}
	}

	/**
	 * Returns the starter for this task
	 *
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getCreatedBy() {
		if (isset($this->createdBy)) {
			return $this->persistenceManager->getObjectByIdentifier($this->createdBy, 'Beech\Party\Domain\Model\Person');
		}
		return NULL;
	}

	/**
	 * Sets the priority, accepts one of the self::PRIORITY_* constants
	 *
	 * @param integer $priority
	 * @return void
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
	}

	/**
	 * Returns the priority
	 *
	 * @return integer
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * Set the dateTime of creation
	 *
	 * @param \DateTime $creationDateTime
	 * @return void
	 */
	public function setCreationDateTime(\DateTime $creationDateTime = NULL) {
		if ($creationDateTime === NULL) {
			$creationDateTime = new \DateTime();
		}
		$this->creationDateTime = $creationDateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreationDateTime() {
		return $this->creationDateTime;
	}

	/**
	 * Set the dateTime this task was set to closed
	 *
	 * @param \DateTime $closeDateTime
	 * @return void
	 */
	public function setCloseDateTime(\DateTime $closeDateTime) {
		$this->closeDateTime = $closeDateTime;
		$this->closed = TRUE;
	}

	/**
	 * @return \DateTime
	 */
	public function getCloseDateTime() {
		return $this->closeDateTime;
	}

	/**
	 * Determine if a task is set closed
	 *
	 * @return boolean
	 */
	public function isClosed() {
		return $this->closed;
	}

	/**
	 * Close the current task
	 *
	 * @return void
	 */
	public function close() {
		$this->setCloseDateTime(new \DateTime());
	}

}

?>