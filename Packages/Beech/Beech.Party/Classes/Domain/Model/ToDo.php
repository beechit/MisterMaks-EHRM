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
	 * The arguments for this task
	 *
	 * @var string
	 */
	protected $arguments;

	/**
	 * The action
	 *
	 * @var string
	 */
	protected $action;

	/**
	 * The controller
	 *
	 * @var string
	 */
	protected $controller;

	/**
	 * True if task is finished
	 *
	 * @var boolean
	 */
	protected $archived;

	/**
	 * The datetime the task is archived
	 * @var \DateTime
	 * @ORM\Column(nullable=true)
	 */
	protected $archivedDateTime;

	/**
	 * Construct the object, sets default value for dateTime
	 */
	public function __construct() {
		$this->dateTime = new \DateTime();
		$this->dateTime->modify('now');
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
	 * Sets the action
	 *
	 * @param string $action
	 * @return void
	 */
	public function setAction($action) {
		$this->action = $action;
	}

	/**
	 * Returns the action
	 *
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * Sets the controller
	 *
	 * @param string $controller
	 * @return void
	 */
	public function setController($controller) {
		$this->controller = $controller;
	}

	/**
	 * Returns the controller
	 *
	 * @return string
	 */
	public function getController() {
		return $this->controller;
	}

	/**
	 * Serialized array with arguments
	 *
	 * @param string $arguments
	 * @return void
	 */
	public function setArguments($arguments) {
		$this->arguments = $arguments;
	}

	/**
	 * Returns array with arguments
	 *
	 * @return array
	 */
	public function getArguments() {
		return unserialize($this->arguments);
	}

	/**
	 * Archive the task
	 *
	 * @param boolean $archived
	 * @return void
	 */
	public function setArchived($archived) {
		$this->archived = $archived;
		$this->setArchivedDateTime(new \DateTime());
	}

	/**
	 * @return boolean
	 */
	public function getArchived() {
		return $this->archived;
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

}
?>
