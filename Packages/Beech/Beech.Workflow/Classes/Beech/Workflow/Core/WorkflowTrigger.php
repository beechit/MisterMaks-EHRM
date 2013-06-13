<?php
namespace Beech\Workflow\Core;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-05-2013 15:42
 * All code (c) Beech Applications B.V. all rights reserved
 */


use TYPO3\Flow\Annotations as Flow;

class WorkflowTrigger {

	/**
	 * @var \Beech\Workflow\Core\WorkflowConfigurationManager
	 * @Flow\Inject
	 */
	protected $workflowConfigurationManager;

	/**
	 * @var string
	 */
	protected $className;

	/**
	 * @var string
	 */
	protected $action;

	/**
	 * The conditions
	 *
	 * @var array
	 */
	protected $conditions;

	/**
	 * @param $settings
	 */
	protected function __construct($settings) {
		foreach($settings as $key => $value) {
			if(property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * Check conditions
	 */
	protected function checkConditions($object) {
		if(!is_array($this->conditions) || count($this->conditions) == 0) {
			return TRUE;
		}
		foreach ($this->conditions as $configuration) {
			$condition = $this->workflowConfigurationManager->createHandlerInstance($configuration, $object);
			if($condition instanceof \Beech\Workflow\Core\ValidatorInterface && !$condition->isValid()) {
				return FALSE;
			}
		}
		return TRUE;
	}

	/**
	 * @param string $action
	 * @param object $object
	 * @return bool
	 */
	public function match($action, $object) {
		if($this->action === $action && $this->className === get_class($object) && $this->checkConditions($object)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/**
	 * Set action
	 *
	 * @param string $action
	 */
	public function setAction($action) {
		$this->action = $action;
	}

	/**
	 * Get action
	 *
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * Set className
	 *
	 * @param string $className
	 */
	public function setClassName($className) {
		$this->className = $className;
	}

	/**
	 * Get className
	 *
	 * @return string
	 */
	public function getClassName() {
		return $this->className;
	}
}