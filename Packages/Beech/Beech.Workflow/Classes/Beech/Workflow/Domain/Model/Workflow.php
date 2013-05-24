<?php
namespace Beech\Workflow\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 24-05-2013 15:38
 * All code (c) Beech Applications B.V. all rights reserved
 */

use Beech\Workflow\Core\WorkflowTrigger;

/**
 * Class Workflow (container class arround YAML settings)
 *
 * @package Beech\Workflow\Domain\Model
 */
class Workflow {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\WorkflowTrigger>
	 */
	protected $triggers;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\ActionInterface>
	 */
	protected $actions;

	/**
	 * @param string $name
	 * @param array $settings
	 */
	public function __construct($name, $settings) {

		$this->initArrayCollections();

		$this->name = $name;

		foreach($settings as $key => $value) {
			switch($key) {
				case 'triggers':
					foreach($value as $tmp) {
						$this->triggers->add(new WorkflowTrigger($tmp));
					}
					break;
			}
		}
	}

	/**
	 * Init array collections
	 */
	protected function initArrayCollections() {
		$this->triggers = new \Doctrine\Common\Collections\ArrayCollection();
		$this->actions = new \Doctrine\Common\Collections\ArrayCollection();
	}

	/**
	 * @param string $action
	 * @param Object $object
	 * @return bool
	 */
	public function matchTriggers($action, $object) {
		foreach($this->triggers as $trigger) {
			if($trigger->match($action, $object)) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set triggers
	 *
	 * @param array $triggers
	 */
	public function setTriggers($triggers) {
		$this->triggers->clear();
		foreach($triggers as $trigger) {
			$this->triggers->add($triggers);
		}
	}

	/**
	 * Get triggers
	 *
	 * @return \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\WorkflowTrigger>
	 */
	public function getTriggers() {
		return $this->triggers;
	}

}