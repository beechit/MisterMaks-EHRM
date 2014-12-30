<?php
namespace Beech\Workflow\Core;

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
	public function __construct($settings) {
		foreach ($settings as $key => $value) {
			if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * Check conditions
	 */
	protected function checkConditions($object) {
		if (!is_array($this->conditions) || count($this->conditions) == 0) {
			return TRUE;
		}
		foreach ($this->conditions as $configuration) {
			$condition = $this->workflowConfigurationManager->createHandlerInstance($configuration, $object);
			if ($condition instanceof \Beech\Workflow\Core\ValidatorInterface && !$condition->isValid()) {
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
		if ($this->action === $action && $this->className === get_class($object) && $this->checkConditions($object)) {
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

?>