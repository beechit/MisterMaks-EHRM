<?php
namespace Beech\Task\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-08-12 10:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Task\Domain\Factory\TaskFactory;
use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * A Task Model
 *
 * @ODM\Document(indexed=true)
 */
class Task extends \Beech\Ehrm\Domain\Model\Document {

	const PRIORITY_LOW = 0;
	const PRIORITY_NORMAL = 1;
	const PRIORITY_HIGH = 2;
	const PRIORITY_IMMEDIATE = 3;

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
	 * @Flow\Validate(type="NotEmpty")
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
	 * @ODM\Index
	 */
	protected $createdBy;

	/**
	 * The person who closed the task
	 * Property is nullable, because a task can be started by the system instead of a user
	 *
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ODM\Field(type="string")
	 */
	protected $closedBy;

	/**
	 * Priority of this task 0-3
	 *
	 * @var integer
	 * @Flow\Validate(type="NumberRange", options={ "minimum"=0, "maximum"=3 })
	 * @ODM\Field(type="integer")
	 * @ODM\Index
	 */
	protected $priority = self::PRIORITY_NORMAL;

	/**
	 * Priotiry at first save
	 *
	 * @var integer
	 * @ODM\Field(type="integer")
	 */
	protected $initialPriority;

	/**
	 * The dateTime this task was created
	 *
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 */
	protected $creationDateTime;

	/**
	 * The datetime this task has to be done
	 *
	 * @var \DateTime
	 * @ODM\Field(type="datetime")
	 */
	protected $endDateTime;

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
	 * @var boolean
	 */
	protected $closeableByAssignee = FALSE;

	/**
	 * Interval in days to increase priority of task to next level
	 * Interval is calculate so that on the given interval before
	 * end date task has highest priority
	 * see \DateInterval::createFromDateString()
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $increasePriorityInterval;

	/**
	 * Interval in days to escalation priority of task to next level
	 * Interval is calculate so that on the given interval before
	 * end date task has highest priority
	 * see \DateInterval::createFromDateString()
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $escalationInterval;

	/**
	 * @var \Beech\Task\Domain\Model\Task
	 * @ODM\ReferenceOne(targetDocument="\Beech\Task\Domain\Model\Task", cascade={"persist"})
	 */
	protected $escalatedTask;

	/**
	 * Workflow Action that initiated this task
	 *
	 * @var \Beech\Workflow\Domain\Model\Action
	 * @ODM\ReferenceOne(targetDocument="\Beech\Workflow\Domain\Model\Action")
	 */
	protected $action;

	/**
	 * Initialize object
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->setCreatedBy();
	}

	/**
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	protected function getCurrentParty() {
		if ($this->securityContext->canBeInitialized()
			&& $this->securityContext->getAccount() instanceof \TYPO3\Flow\Security\Account
			&& $this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person
		) {
			return $this->securityContext->getAccount()->getParty();
		}
		return NULL;
	}

	/**
	 * @return string
	 */
	protected function getCurrentPartyIdentifier() {
		$currentParty = $this->getCurrentParty();

		if ($currentParty !== NULL) {
			return $this->persistenceManager->getIdentifierByObject($currentParty);
		}
		return NULL;
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
			return $this->persistenceManager->getObjectByIdentifier($this->assignedTo, 'Beech\Party\Domain\Model\Person', TRUE);
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
		if ($creator === NULL) {
			$this->createdBy = $this->getCurrentPartyIdentifier();
		} else {
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
			return $this->persistenceManager->getObjectByIdentifier($this->createdBy, 'Beech\Party\Domain\Model\Person', TRUE);
		}
		return NULL;
	}

	/**
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $person
	 * @return void
	 */
	public function setClosedBy(\TYPO3\Party\Domain\Model\AbstractParty $person = NULL) {
		if ($person === NULL) {
			$this->closedBy = $this->getCurrentPartyIdentifier();
		} else {
			$this->closedBy = $this->persistenceManager->getIdentifierByObject($person, 'Beech\Party\Domain\Model\Person');
		}
	}

	/**
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getClosedBy() {
		if (isset($this->closedBy)) {
			return $this->persistenceManager->getObjectByIdentifier($this->closedBy, 'Beech\Party\Domain\Model\Person', TRUE);
		}
		return NULL;
	}

	/**
	 * Sets the priority, accepts 0-3
	 *
	 * @param integer $priority
	 * @return void
	 */
	public function setPriority($priority) {
		// set initial priority
		if ($this->getId() && $this->initialPriority === NULL) {
			$this->initialPriority = $this->priority;
		} elseif (!$this->getId()) {
			$this->initialPriority = $priority;
		}
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
	 * Get initialPriority
	 *
	 * @return int
	 */
	public function getInitialPriority() {
		if ($this->initialPriority === NULL) {
			return self::PRIORITY_NORMAL;
		}
		return $this->initialPriority;
	}

	/**
	 * Set endDateTime
	 *
	 * @param \DateTime $endDateTime
	 */
	public function setEndDateTime($endDateTime) {
		$this->endDateTime = $endDateTime;
	}

	/**
	 * Get endDateTime
	 *
	 * @return \DateTime
	 */
	public function getEndDateTime() {
		return $this->endDateTime;
	}

	/**
	 * Set the dateTime of creation
	 *
	 * @param \DateTime $creationDateTime
	 * @return void
	 */
	public function setCreationDateTime(\DateTime $creationDateTime = NULL) {
		if ($creationDateTime === NULL) {
			$creationDateTime = new \TYPO3\Flow\Utility\Now();
		}
		$this->creationDateTime = $creationDateTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreationDateTime() {
		if ($this->creationDateTime === NULL) {
			$this->creationDateTime = new \TYPO3\Flow\Utility\Now();
		}
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
	 * @return boolean
	 */
	public function getCanBeClosedByCurrentParty() {
		if (!empty($this->createdBy) && $this->createdBy === $this->getCurrentPartyIdentifier()) {
			return TRUE;
		}

		if ($this->closeableByAssignee === TRUE && $this->assignedTo === $this->getCurrentPartyIdentifier()) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Close the current task
	 *
	 * @throws \Beech\Task\Exception
	 * @return void
	 */
	public function close() {
		if (!$this->getCanBeClosedByCurrentParty()) {
			throw new \Beech\Task\Exception('This task can not be closed by current party');
		}

		$this->closedBy = $this->getCurrentPartyIdentifier();
		$this->setCloseDateTime(new \TYPO3\Flow\Utility\Now());
		$this->closed = TRUE;

		if ($this->escalatedTask && !$this->escalatedTask->isClosed()) {
			$this->escalatedTask->closedBy = $this->closedBy;
			$this->setCloseDateTime($this->getCloseDateTime());
			$this->escalatedTask->closed = TRUE;
		}
	}

	/**
	 * @param boolean $closeableByAssignee
	 */
	public function setCloseableByAssignee($closeableByAssignee) {
		$this->closeableByAssignee = $closeableByAssignee;
	}

	/**
	 * @return boolean
	 */
	public function getCloseableByAssignee() {
		return $this->closeableByAssignee;
	}

	/**
	 * Set action
	 *
	 * @param \Beech\Workflow\Domain\Model\Action $action
	 */
	public function setAction(\Beech\Workflow\Core\ActionInterface $action) {
		$this->action = $action;
	}

	/**
	 * Get action
	 *
	 * @return \Beech\Workflow\Domain\Model\Action
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * Set increasePriorityInterval
	 * see \DateInterval::createFromDateString() for format
	 *
	 * @param string $increasePriorityInterval
	 */
	public function setIncreasePriorityInterval($increasePriorityInterval) {
		$this->increasePriorityInterval = $increasePriorityInterval;
	}

	/**
	 * Get increasePriorityInterval
	 *
	 * @return string
	 */
	public function getIncreasePriorityInterval() {
		return $this->increasePriorityInterval;
	}

	/**
	 * Get DateTime of next moment that the priority will be increased
	 *
	 * @return null|\DateTime
	 */
	public function getNextPriorityIncreaseDateTime() {
		$info = $this->nextPriorityIncreaseInfo();
		return $info['date'];
	}

	/**
	 * Determin date of next PriorityIncrease and new priority
	 *
	 * @return array
	 */
	protected function nextPriorityIncreaseInfo() {

		$info = array('priority' => $this->priority, 'date' => NULL);

		if ($this->priority === self::PRIORITY_IMMEDIATE) {
			return $info;
		}

		if (!$this->increasePriorityInterval) {
			$info['priority']++;
			return $info;
		}

		$interval = \DateInterval::createFromDateString($this->increasePriorityInterval);
		$now = new \TYPO3\Flow\Utility\Now();

		if ($this->endDateTime) {
			$priority = self::PRIORITY_IMMEDIATE;
			$date = clone $this->endDateTime;
			do {
				$date->sub(\DateInterval::createFromDateString($this->increasePriorityInterval));
				$priority--;
			} while ($date > $now && ($this->priority + 1) < $priority);

			$info['date'] = $date;
			$info['prioriry'] = $priority;
		} else {
			$steps = self::PRIORITY_IMMEDIATE - $this->priority;
			$date = clone $this->creationDateTime;
			$priority = $this->initialPriority();

			do {
				$date->add($interval);
				$priority++;
			} while ($date < $now && $priority < self::PRIORITY_IMMEDIATE);

			$info['date'] = $date;
			$info['prioriry'] = $priority;
		}

		return $info;
	}

	/**
	 * Increase priority of Task
	 * When no priority is passed then the next priority will
	 * be calculated and used
	 *
	 * @param integer $newPriority
	 */
	public function increasePriority($newPriority = NULL) {
		if ($newPriority !== NULL) {
			$this->priority = $newPriority;
		} else {
			$info = $this->nextPriorityIncreaseInfo();
				// only update when new priority is higher than current
				// to prevent decreasing priority when increaded manualy
			if ($this->priority < $info['priority']) {
				$this->priority = $info['priority'];
			}
		}
	}

	/**
	 * Set EscalationInterval
	 * see \DateInterval::createFromDateString() for format
	 *
	 * @param string $escalationInterval
	 */
	public function setEscalationInterval($escalationInterval) {
		$this->escalationInterval = $escalationInterval;
	}

	/**
	 * Get EscalationInterval
	 *
	 * @return string
	 */
	public function getEscalationInterval() {
		return $this->escalationInterval;
	}

	/**
	 * Escalate task to manager of Assigned Party
	 */
	public function escalate() {
		if ($this->getAssignedTo() && $this->getAssignedTo()->getManager() !== NULL) {
			$this->escalatedTask = TaskFactory::createTask(self::PRIORITY_IMMEDIATE, 'ESCALATION', $this->getAssignedTo(), FALSE);
			$this->escalatedTask->setEndDateTime($this->getEndDateTime());
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Get DateTime that the task wil be escalated
	 *
	 * When endDate is set then the interval is decreaced from this
	 * When no endDate is set the interval is added to the creationDateTime
	 * No escalationInterval or task is already escalated NULL is returned
	 *
	 * @return \DateTime|null
	 */
	public function getEscalationDateTime() {
		if ($this->isEscalated() || !$this->escalationInterval) {
			return NULL;
		} elseif ($this->escalationInterval && $this->endDateTime) {
			$endDateTime = clone $this->endDateTime;
			$endDateTime->sub(\DateInterval::createFromDateString($this->escalationInterval));
			return $endDateTime;
		} else {
			$creationDateTime = clone $this->creationDateTime;
			$creationDateTime->add(\DateInterval::createFromDateString($this->escalationInterval));
			return $creationDateTime;
		}
	}

	/**
	 * Set escalatedTask
	 *
	 * @param \Beech\Task\Domain\Model\Task $escalatedTask
	 */
	public function setEscalatedTask($escalatedTask) {
		$this->escalatedTask = $escalatedTask;
	}

	/**
	 * Get escalatedTask
	 *
	 * @return \Beech\Task\Domain\Model\Task
	 */
	public function getEscalatedTask() {
		return $this->escalatedTask;
	}

	/**
	 * Check if task is escalated
	 *
	 * @return bool
	 */
	public function isEscalated() {
		return $this->escalatedTask !== NULL;
	}
}

?>