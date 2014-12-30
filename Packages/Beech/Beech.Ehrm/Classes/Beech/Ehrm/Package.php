<?php
namespace Beech\Ehrm;

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

/**
 * The Beech IT EHRM Package
 *
 */
class Package extends BasePackage {

	/**
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap The current bootstrap
	 * @return void
	 */
	public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		$dispatcher = $bootstrap->getSignalSlotDispatcher();
		$dispatcher->connect('TYPO3\Flow\Configuration\ConfigurationManager', 'configurationManagerReady',
			function ($configurationManager) {
				$configurationManager->registerConfigurationType('Models', \TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT, TRUE);
			}
		);
		$dispatcher->connect('TYPO3\Flow\Configuration\ConfigurationManager', 'configurationManagerReady',
			function ($configurationManager) {
				$configurationManager->registerConfigurationType('Wizards', \TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_PROCESSING_TYPE_DEFAULT, TRUE);
			}
		);

		$dispatcher->connect('Beech\Task\Domain\Repository\TaskRepository', 'taskCreated', 'Beech\Ehrm\Service\Notification', 'taskCreated');
		$dispatcher->connect('Beech\Task\Domain\Repository\TaskRepository', 'taskChanged', 'Beech\Ehrm\Service\Notification', 'taskChanged');
	}
}

?>