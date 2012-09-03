<?php
namespace Beech\Party\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Pieter Geurts <pieter@aleto.nl>
 * Date: 01-08-12 10:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * A To-Do Model
 *
 * @FLOW3\Entity
 */
class ToDo {

	const PRIORITY_LOW = 'low';
	const PRIORITY_NORMAL = 'normal';
	const PRIORITY_HIGH = 'high';
	const PRIORITY_VERY_HIGH = 'veryHigh';

	/**
	 * The task name
	 *
	 * @var string
	 */
	protected $task;

	/**
	 * The controllername to complete this task
	 *
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $controllerName;

	/**
	 * The actionname to complete this task
	 *
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $controllerAction;

	/**
	 * The arguments to complete this task
	 *
	 * @var string
	 * @ORM\Column(nullable=true)
	 */
	protected $controllerArguments;

	/**
	 * The task owner
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @ORM\ManyToOne
	 */
	protected $owner;

	/**
	 * The task starter
	 *
	 * @var \Beech\Party\Domain\Model\Person
	 * @ORM\ManyToOne
	 */
	protected $starter;

	/**
	 * The dateTime
	 *
	 * @var \DateTime
	 */
	protected $dateTime;

	/**
	 * Priority of this task 0-100
	 *
	 * @var integer
	 * @FLOW3\Validate(type="NumberRange", options={ "minimum"=0, "maximum"=100 })
	 */
	protected $priority;

	/**
	 * The datetime the task is archived
	 * @var \DateTime
	 * @ORM\Column(nullable=true)
	 */
	protected $archivedDateTime;

	/**
	 * Is true if a user may archive an item
	 *
	 * @var boolean
	 */
	protected $userMayArchive;

	/**
	 * The url to execute this task
	 *
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Beech\Party\Domain\Model\Notification>
	 * @ORM\OneToMany(mappedBy="todo",cascade={"persist"})
	 */
	protected $notifications;

	/**
	 * Construct the object, sets default value for dateTime
	 */
	public function __construct() {
		$this->dateTime = new \DateTime();

		$this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * Sets the dateTime
	 *
	 * @param string $dateTime
	 */
	public function setDateTime($dateTime) {
		$this->dateTime = $dateTime;
	}

	/**
	 * Returns the dateTime
	 *
	 * @return string
	 */
	public function getDateTime() {
		return $this->dateTime;
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
		return array(	100 => self::PRIORITY_VERY_HIGH,
						75 => self::PRIORITY_HIGH,
						50 => self::PRIORITY_NORMAL,
						25 => self::PRIORITY_LOW);
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
	 * Sets the owner for this task
	 *
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $owner
	 * @return void
	 */
	public function setOwner($owner) {
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
	 * Sets the starter
	 *
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $starter
	 * @return void
	 */
	public function setStarter($starter) {
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
	 * Sets the task name
	 *
	 * @param string $task
	 * @return void
	 */
	public function setTask($task) {
		$this->task = $task;
	}

	/**
	 * Return the task name
	 *
	 * @return string
	 */
	public function getTask() {
		return $this->task;
	}

	/**
	 * Archive the task
	 *
	 * @return void
	 */
	public function setArchived() {
		$this->setArchivedDateTime(new \DateTime());
	}

	/**
	 * Set the archived dateTime
	 *
	 * @param \DateTime $archivedDateTime
	 * @return void
	 */
	public function setArchivedDateTime(\DateTime $archivedDateTime) {
		$this->archivedDateTime = $archivedDateTime;
	}

	/**
	 * Returns the archived dateTime
	 *
	 * @return \DateTime
	 */
	public function getArchivedDateTime() {
		return $this->archivedDateTime;
	}

	/**
	 * Adds an notification to the current to-do.
	 *
	 * @param \Beech\Party\Domain\Model\Notification $notification
	 * @return void
	 */
	public function addNotification(\Beech\Party\Domain\Model\Notification $notification) {
		$notification->setToDo($this);
		$this->notifications->add($notification);
	}

	/**
	 * Sets the controllerName
	 *
	 * @param string $controllerName
	 * @return void
	 */
	public function setControllerName($controllerName) {
		$this->controllerName = $controllerName;
	}

	/**
	 * Returns the controllerName
	 *
	 * @return string
	 */
	public function getControllerName() {
		return $this->controllerName;
	}

	/**
	 * Sets the controllerAction
	 *
	 * @param string $controllerAction
	 * @return void
	 */
	public function setControllerAction($controllerAction) {
		$this->controllerAction = $controllerAction;
	}

	/**
	 * Returns the controllerAction
	 *
	 * @return string
	 */
	public function getControllerAction() {
		return $this->controllerAction;
	}

	/**
	 * Sets the controllerArguments
	 *
	 * @param string $controllerArguments
	 * @return void
	 */
	public function setControllerArguments($controllerArguments) {
		$this->controllerArguments = $controllerArguments;
	}

	/**
	 * Returns the controllerArguments
	 *
	 * @return string
	 */
	public function getControllerArguments() {
		return unserialize($this->controllerArguments);
	}

	/**
	 * Sets userMayArchive
	 *
	 * @param boolean $userMayArchive
	 * @return void
	 */
	public function setUserMayArchive($userMayArchive) {
		$this->userMayArchive = $userMayArchive;
	}

	/**
	 * Returns userMayArchive
	 *
	 * @return boolean
	 */
	public function getUserMayArchive() {
		return $this->userMayArchive;
	}

}
?>
