<?php
namespace Beech\Workflow\OutputHandlers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 31-05-2013 16:22
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

class TaskOutputHandler extends \Beech\Workflow\Core\AbstractOutputHandler implements \Beech\Workflow\Core\OutputHandlerInterface {

	/**
	 * @var \Beech\Task\Domain\Repository\TaskRepository
	 * @Flow\Inject
	 */
	protected $taskRepository;

	/**
	 * @var \TYPO3\Flow\I18n\Service
	 * @Flow\Inject
	 */
	protected $localizationService;

	/**
	 * The task description
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * Description replacements
	 *
	 * @var array
	 */
	protected $descriptionReplacements;

	/**
	 * Priority of this task 0-3
	 *
	 * @var integer
	 */
	protected $priority;

	/**
	 * The task owner
	 *
	 * @var \TYPO3\Party\Domain\Model\AbstractParty
	 */
	protected $assignedTo;

	/**
	 * @var string
	 */
	protected $increasePriorityInterval;

	/**
	 * @var \TYPO3\Flow\I18n\Locale
	 */
	protected $defaultLocal;

	/**
	 * Interval in days to escalation priority of task to next level
	 * see Beech\Task\Domain\Model\Taks::escalationInterval
	 *
	 * @var string
	 */
	protected $escalationInterval;

	/**
	 * The datetime the task has to be done
	 *
	 * @var \DateTime
	 */
	protected $endDateTime;

	/**
	 * Link options
	 *
	 * @var array
	 */
	protected $link;

	/**
	 * Set assignedTo
	 *
	 * @param \TYPO3\Party\Domain\Model\AbstractParty $assignedTo
	 */
	public function setAssignedTo($assignedTo) {
		$this->assignedTo = $assignedTo;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Set descriptionReplacements
	 *
	 * @param array $descriptionReplacements
	 */
	public function setDescriptionReplacements($descriptionReplacements) {
		$this->descriptionReplacements = $descriptionReplacements;
	}

	/**
	 * Set increasePriorityInterval
	 *
	 * @param string $increasePriorityInterval
	 */
	public function setIncreasePriorityInterval($increasePriorityInterval) {
		$this->increasePriorityInterval = $increasePriorityInterval;
	}

	/**
	 * Sets the priority, accepts 0-3
	 *
	 * @param integer $priority
	 * @return void
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
	}

	/**
	 * Set escalationInterval
	 *
	 * @param string $escalationInterval
	 */
	public function setEscalationInterval($escalationInterval) {
		$this->escalationInterval = $escalationInterval;
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
	 * Execute the output handler class
	 *
	 * @return mixed
	 */
	public function invoke() {
		$task = \Beech\Task\Domain\Factory\TaskFactory::createTask();
		$task->setAssignedTo($this->assignedTo);
		$task->setDescription($this->getParsedDescription());
		if ($this->priority !== NULL) {
			$task->setPriority($this->priority);
		}
		if ($this->escalationInterval !== NULL) {
			$task->setEscalationInterval($this->escalationInterval);
		}
		if ($this->increasePriorityInterval !== NULL) {
			$task->setIncreasePriorityInterval($this->increasePriorityInterval);
		}
		if ($this->endDateTime !== NULL) {
			$task->setEndDateTime($this->endDateTime);
		}
		$task->setLink($this->getLink());
		$task->setAction($this->action);
		$this->taskRepository->add($task);
	}

	/**
	 * Get parsed description
	 *
	 * @return string
	 */
	public function getParsedDescription() {
		if (!is_array($this->descriptionReplacements) || count($this->descriptionReplacements) == 0) {
			return $this->description;
		}
		return vsprintf($this->description, $this->processDescriptionReplacements());
	}

	/**
	 * Transform all replacements to simple types
	 *
	 * @return array
	 */
	protected function processDescriptionReplacements() {
		$replacements = array();

		foreach ($this->descriptionReplacements as $key => $value) {
			if ($value instanceof \DateTime) {
				$formatter = new \TYPO3\Flow\I18n\Formatter\DatetimeFormatter();
				$value = $formatter->formatDate($value, $this->localizationService->getConfiguration()->getCurrentLocale());
			}
			if (is_object($value)) {
				$value = (string)$value;
			}
			$replacements[$key] = $value;
		}
		return $replacements;
	}

	/**
	 * Set link options
	 *
	 * @param array $link
	 */
	public function setLink($link) {
		$this->link = $link;
	}

	/**
	 * Get link
	 *
	 * @return \Beech\Ehrm\Domain\Model\Link|null
	 */
	public function getLink() {
		$link = NULL;
		if (is_array($this->link)
			&& array_key_exists('packageKey', $this->link)
			&& array_key_exists('controllerName', $this->link)
			&& array_key_exists('actionName', $this->link)
		) {
			$link = new \Beech\Ehrm\Domain\Model\Link();
			$link->setPackageKey($this->link['packageKey']);
			$link->setControllerName($this->link['controllerName']);
			$link->setActionName($this->link['actionName']);

			if (!empty($this->link['subpackageKey'])) {
				$link->setSubpackageKey($this->link['subpackageKey']);
			}
			if (!empty($this->link['arguments'])) {
				$link->setArguments($this->link['arguments']);
			}
			if (!empty($this->link['modal'])) {
				$link->setModal($this->link['modal']);
			}
		}
		return $link;
	}
}

?>