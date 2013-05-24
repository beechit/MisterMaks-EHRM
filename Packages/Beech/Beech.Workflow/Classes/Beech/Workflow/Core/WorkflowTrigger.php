<?php
namespace Beech\Workflow\Core;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-05-2013 15:42
 * All code (c) Beech Applications B.V. all rights reserved
 */


class WorkflowTrigger {

	/**
	 * @var string
	 */
	protected $className;

	/**
	 * @var string
	 */
	protected $action;

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
	 * @param string $action
	 * @param object $object
	 * @return bool
	 */
	public function match($action, $object) {
		if($this->action === $action && $this->className === get_class($object)) {
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