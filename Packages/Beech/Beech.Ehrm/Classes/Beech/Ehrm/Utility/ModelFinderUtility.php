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
	 * @var array
	 */
	protected $modelConfigurations;

	/**
	 * @var array
	 */
	protected $ignoredProperties;

	/**
	 * @param string $packageKey
	 * @param array $classNames
	 * @return void
	 */
	public function findModelImplementations($packageKey, array &$classNames) {
			// TODO: OPTIMIZE and CLEANUP!
		$this->ignoredProperties = $this->configurationManager->getConfiguration('Settings', 'Radmiraal.Emberjs.properties._exclude');

		$this->modelConfigurations = $this->configurationManager->getConfiguration('Models');
		foreach ($this->modelConfigurations as $modelName => $modelConfiguration) {
			$modelName = str_replace('.', '\\', $modelName);
			if (isset($classNames[$modelName])) {
				continue;
			}

			$classNames[$modelName] = $this->findModelProperties($modelName);
		}
	}

	/**
	 * @param string $modelName
	 * @return array
	 */
	public function findModelProperties($modelName) {
		$result = array();
		if (isset($this->modelConfigurations[$modelName]['properties']) && is_array($this->modelConfigurations[$modelName]['properties'])) {
			foreach ($this->modelConfigurations[$modelName]['properties'] as $propertyName => $propertyConfiguration) {
				if (is_array($this->ignoredProperties) && in_array($propertyName, $this->ignoredProperties)) {
					continue;
				}

					// TODO: Add lookup for relations and add them to the modelImplementations
				$result[$propertyName] = array(
					'type' => isset($propertyConfiguration['type']) ? $propertyConfiguration['type'] : 'string'
				);
			}
		}
		return $result;
	}

	/**
	 * @param string $modelName
	 * @return boolean
	 */
	public function canRead($modelName) {
		return isset($this->modelConfigurations[$modelName]);
	}
}

?>