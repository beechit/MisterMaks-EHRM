<?php
namespace Beech\Workflow\Domain\Repository;

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

use Beech\Workflow\Domain\Model\Workflow;
use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Workflows
 * Readed from YAML files
 *
 * @Flow\Scope("singleton")
 */
class WorkflowRepository {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @var array
	 */
	protected $_parsed_settings;

	/**
	 * Find all in YAML defined workflows
	 *
	 * @return array
	 */
	public function findAll() {
		if ($this->_parsed_settings === NULL) {
			$this->_parsed_settings = array();

			foreach ($this->configurationManager->getConfiguration('Workflows') as $workflow => $settings) {
				$this->_parsed_settings[] = new Workflow($workflow, $settings);
			}
		}
		return $this->_parsed_settings;
	}

	/**
	 * Find Workflow by trigger
	 *
	 * @param $action
	 * @param $object
	 * @return array
	 */
	public function findAllByTrigger($action, $object) {

		$className = get_class($object);
		$workflows = array();

		foreach ($this->findAll() as $workflow) {
			if ($workflow->matchTriggers($action, $object)) {
				$workflows[] = $workflow;
			}
		}

		return $workflows;
	}

}

?>