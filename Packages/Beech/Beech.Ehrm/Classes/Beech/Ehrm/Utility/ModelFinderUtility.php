<?php
namespace Beech\Ehrm\Utility;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 2/26/13 2:06 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 */
class ModelFinderUtility implements \Radmiraal\Emberjs\Utility\ModelFinderUtilityInterface {

	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 * @Flow\Inject
	 */
	protected $reflectionService;

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @param string $packageKey
	 * @param array $classNames
	 * @return void
	 */
	public function findModelImplementations($packageKey, array &$classNames) {
			// TODO: OPTIMIZE and CLEANUP!
		$ignoredProperties = $this->configurationManager->getConfiguration('Settings', 'Radmiraal.Emberjs.properties._exclude');

		$modelConfigurations = $this->configurationManager->getConfiguration('Models');
		foreach ($modelConfigurations as $modelName => $modelConfiguration) {
			$modelName = str_replace('.', '\\', $modelName);
			if (isset($classNames[$modelName])) {
				continue;
			}

			$classNames[$modelName] = array();

			if (isset($modelConfiguration['properties']) && is_array($modelConfiguration['properties'])) {
				foreach ($modelConfiguration['properties'] as $propertyName => $propertyConfiguration) {
					if (is_array($ignoredProperties) && in_array($propertyName, $ignoredProperties)) {
						continue;
					}
						// TODO: Add lookup for relations and add them to the modelImplementations
					$classNames[$modelName][$propertyName] = array(
						'type' => isset($propertyConfiguration['type']) ? $propertyConfiguration['type'] : 'string'
					);
				}
			}
		}
	}
}

?>