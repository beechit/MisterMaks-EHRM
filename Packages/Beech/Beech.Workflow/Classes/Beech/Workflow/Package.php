<?php
namespace Beech\Workflow;

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

use TYPO3\Flow\Package\Package as BasePackage;
use TYPO3\Flow\Annotations as Flow;

/**
 * Package base class of the Beech.Workflow package.
 *
 * @Flow\Scope("singleton")
 */
class Package extends BasePackage {

	/**
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap
	 * @return void
	 */
	public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		$dispatcher = $bootstrap->getSignalSlotDispatcher();
		$dispatcher->connect('TYPO3\Flow\Configuration\ConfigurationManager', 'configurationManagerReady',
			function ($configurationManager) {
				$configurationManager->registerConfigurationType('Workflows', \TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT, TRUE);
			}
		);

		if (!isset($_SERVER['SHELL'])) {
				// TODO Move this to another place later on
				// 1) this could be a performance killer
				// 2) the check for $_SERVER['SHELL'] is dirty, but has to be done to be able to migrate the database
			$dispatcher->connect('TYPO3\Flow\Mvc\Dispatcher', 'beforeControllerInvocation', 'Beech\Workflow\Workflow\ActionDispatcher', 'run');
		}
	}
}

?>