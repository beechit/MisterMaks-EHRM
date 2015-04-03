<?php
namespace Beech\Ehrm\Utility;

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