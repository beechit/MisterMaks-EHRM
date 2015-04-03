<?php
namespace Beech\Workflow\Domain\Model;

/*
/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Beech\Workflow\Core\WorkflowTrigger;
use Beech\Workflow\Workflow\ActionFactory;

/**
 * Class Workflow (container class arround YAML settings)
 *
 * @package Beech\Workflow\Domain\Model
 */
class Workflow {

	/**
	 * The settings to parse
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\WorkflowTrigger>
	 */
	protected $triggers;

	/**
	 * @var boolean
	 */
	protected $triggersLoaded = FALSE;

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
		$this->settings = $settings;
	}

	/**
	 * Parse settings to load the triggers
	 */
	protected function loadTriggers() {
		if (!$this->triggersLoaded) {
			$this->triggersLoaded = TRUE;
			if (!empty($this->settings['triggers'])) {
				foreach ($this->settings['triggers'] as $tmp) {
					$this->triggers->add(new WorkflowTrigger($tmp));
				}
			}
		}
	}

	/**
	 * Load actions from config
	 * only if actions aren't set already
	 */
	protected function loadActions() {
		if ($this->actions === NULL) {
			$this->initActionsArrayCollection();
			$actionFactory = new ActionFactory();
			$actionFactory->setWorkflowName($this->getName());
			foreach ($actionFactory->create() as $action) {
				$this->actions->add($action);
			}
		}
	}

	/**
	 * Init array collections
	 */
	protected function initArrayCollections() {
		$this->triggers = new \Doctrine\Common\Collections\ArrayCollection();
	}

	protected function initActionsArrayCollection() {
		if ($this->actions === NULL) {
			$this->actions = new \Doctrine\Common\Collections\ArrayCollection();
		}
	}

	/**
	 * @param string $action
	 * @param Object $object
	 * @return bool
	 */
	public function matchTriggers($action, $object) {
		$this->loadTriggers();
		foreach ($this->triggers as $trigger) {
			if ($trigger->match($action, $object)) {
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
		foreach ($triggers as $trigger) {
			$this->triggers->add($triggers);
		}
	}

	/**
	 * Get triggers
	 *
	 * @return \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\WorkflowTrigger>
	 */
	public function getTriggers() {
		$this->loadTriggers();
		return $this->triggers;
	}

	/**
	 * Set actions
	 *
	 * @param \Doctrine\Common\Collections\ArrayCollection $actions
	 */
	public function setActions($actions) {
		$this->initActionsArrayCollection();
		$this->actions->clear();

		foreach ($actions as $action) {
			$this->actions->add($action);
		}
	}

	/**
	 * Get actions
	 *
	 * @return \Doctrine\Common\Collections\ArrayCollection<\Beech\Workflow\Core\ActionInterface>
	 */
	public function getActions() {
		$this->loadActions();
		return $this->actions;
	}

	/**
	 * Add action
	 *
	 * @param \Beech\Workflow\Core\ActionInterface $action
	 */
	public function addAction(\Beech\Workflow\Core\ActionInterface $action) {
		$this->initActionsArrayCollection();
		$this->actions->add($action);
	}

}

?>