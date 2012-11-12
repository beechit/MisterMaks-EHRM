<?php
namespace Beech\Task\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-08-12 10:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A To-Do Model
 *
 * @Flow\Entity
 * @ORM\HasLifecycleCallbacks
 */
class ToDo {

	const PRIORITY_LOW = 'low';
	const PRIORITY_NORMAL = 'normal';
	const PRIORITY_HIGH = 'high';
	const PRIORITY_VERY_HIGH = 'veryHigh';

	/**
	 * The task description
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * The task owner
	 *
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ORM\ManyToOne
	 */
	protected $owner;

	/**
	 * The task starter
	 * Property is nullable, because a task can be started by the system instead of a user
	 *
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 * @ORM\ManyToOne
	 * @ORM\Column(nullable=true)
	 */
	protected $starter;

	/**
	 * Priority of this task 0-100
	 *
	 * @var integer
	 * @Flow\Validate(type="NumberRange", options={ "minimum"=0, "maximum"=100 })
	 */
	protected $priority;

	/**
	 * The dateTime this task was created
	 *
	 * @var \DateTime
	 */
	protected $creationDateTime;

	/**
	 * The datetime this task was closed
	 *
	 * @var \DateTime
	 * @ORM\Column(nullable=true)
	 */
	protected $closeDateTime;

	/**
	 * @var \Beech\Ehrm\Domain\Model\Notification
	 * @ORM\OneToMany(mappedBy="toDo")
	 */
	protected $notifications;

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
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $owner
	 * @return void
	 */
	public function setOwner(\TYPO3\Party\Domain\Model\AbstractParty $owner) {
		$this->owner = $owner;
	}

	/**
	 * Returns the owner for this task
	 *
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * Sets the starter of this task, load the current user if NULL was emitted
	 * We use the top-level AbstractParty class as typehint, because a task can also
	 * be started by a systemprocess
	 *
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $starter
	 * @ORM\PrePersist
	 * @return void
	 */
	public function setStarter(\TYPO3\Party\Domain\Model\AbstractParty $starter = NULL) {
		if ($starter === NULL ) {
			if (is_object($this->securityContext->getAccount())
				&& $this->securityContext->getAccount()->getParty() instanceof \Beech\Party\Domain\Model\Person) {
				$starter = $this->securityContext->getAccount()->getParty();
			}
		}
		$this->starter = $starter;
	}

	/**
	 * Returns the starter for this task
	 *
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 */
	public function getStarter() {
		return $this->starter;
	}

	/**
	 * Sets the priority, value between 0-100
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
	 * Returns the priorities
	 *
	 * @return array
	 */
	public static function getPriorities() {
		return array( 100 => self::PRIORITY_VERY_HIGH,
			75 => self::PRIORITY_HIGH,
			50 => self::PRIORITY_NORMAL,
			25 => self::PRIORITY_LOW);
	}

	/**
	 * Returns the priority in an textual presentation
	 *
	 * @return string
	 */
	public function getPriorityTextual() {
		switch(ceil($this->priority / 25)) {
			case 4:
				return self::PRIORITY_VERY_HIGH;
			case 3:
				return self::PRIORITY_HIGH;
			case 2:
				return self::PRIORITY_NORMAL;
			case 1:
			default:
				return self::PRIORITY_LOW;
		}
	}

	/**
	 * Set the dateTime of creation
	 *
	 * @param \DateTime $creationDateTime
	 * @ORM\PrePersist
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
		return NULL !== $this->closeDateTime;
	}

	/**
	 * Close the current task
	 *
	 * @return void
	 */
	public function close() {
		$this->setCloseDateTime(new \DateTime());
	}

	/**
	 * Set related notifications
	 *
	 * @param $notifications
	 */
	public function setNotifications($notifications) {
		$this->notifications = $notifications;
	}

	/**
	 * Get related notifications
	 *
	 * @return \Beech\Ehrm\Domain\Model\Notification
	 */
	public function getNotifications() {
		return $this->notifications;
	}

}
?>