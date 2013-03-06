<?php
namespace Beech\Ehrm;

/*
 * This source file is proprietary property of Beech Applications B.V.
 *
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
	}
}

?>