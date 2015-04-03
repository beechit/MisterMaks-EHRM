<?php
namespace Beech\Ehrm\Domain;

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
 * Trait which can be used to add getGettablePropertyNames()
 * to a model with automatic lookup in Models.yaml for configured
 * properties.
 */
trait ConfigurableModelTrait {

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @var array
	 * @Flow\Transient
	 */
	protected $gettableProperties = array();

	/**
	 * Model configuration as defined in YAML
	 *
	 * @var array
	 * @Flow\Transient
	 */
	protected $modelConfiguration;

	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 * @Flow\Inject
	 */
	protected $reflectionService;

	/**
	 * @var \TYPO3\Flow\Property\PropertyMapper
	 * @Flow\Inject
	 */
	protected $propertyMapper;

	/**
	 * Processed data
	 *
	 * @var array<mixed>
	 * @Flow\Transient
	 */
	protected $mappedData;

	/**
	 * Get configuration path for model description
	 *
	 * @return string
	 */
	protected function getModelConfigurationPath() {
		$className = preg_replace('/CouchDocument$/', '', get_class($this));
		return str_replace(array('\\'), array('.'), $className);
	}

	/**
	 * Get model configuration
	 */
	protected function getModelConfiguration() {
		if ($this->modelConfiguration === NULL) {
			$this->modelConfiguration = array();
			$modelConfiguration = $this->configurationManager->getConfiguration('Models');
			if (array_key_exists($this->getModelConfigurationPath(), $modelConfiguration)) {
				$this->modelConfiguration = $modelConfiguration[$this->getModelConfigurationPath()]['properties'];
			}
		}
		return $this->modelConfiguration;
	}

	/**
	 * Returns a list of properties of this model. This will be a combination
	 * of class properties and the properties defined in Models.yaml.
	 * The latter would overwrite class properties.
	 *
	 * @return array
	 */
	public function getGettablePropertyNames() {
		if (!empty($this->gettableProperties)) {
			return $this->gettableProperties;
		}

		$this->gettableProperties = array_keys(get_object_vars($this));
		$modelConfiguration = $this->getModelConfiguration();
		if (is_array($modelConfiguration)) {
			$this->gettableProperties = array_merge($this->gettableProperties, array_keys($modelConfiguration));
		}

		foreach ($this->gettableProperties as $index => $property) {
			if (in_array($property, array('data', 'document', 'gettableProperties', 'gettablePropertyNames', 'modelConfiguration', 'mappedData'))
				|| preg_match('/Repository$/', $property)
				|| $this->reflectionService->isPropertyAnnotatedWith(get_class($this), $property, 'TYPO3\Flow\Annotations\Inject')
			) {
				unset($this->gettableProperties[$index]);
			}
		}

		return $this->gettableProperties;
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		if (property_exists($this, $name)) {
			return $this->$name;
		} elseif (isset($this->mappedData[$name])) {
			return $this->mappedData[$name];
		} elseif ($this->getDynamicPropertyType($name)) {

			$rawValue = (isset($this->data[$name]) ? $this->data[$name] : NULL);

			if ($rawValue === NULL) {
				$this->mappedData[$name] = NULL;
			} elseif ($this->getDynamicPropertyType($name) === 'DateTime') {
				$this->mappedData[$name] = $this->propertyMapper->convert(array('date' => $rawValue, 'dateFormat' => 'Y-m-d H:i:s.u'), 'DateTime');
			} else {
				$this->mappedData[$name] = $this->propertyMapper->convert($rawValue, $this->getDynamicPropertyType($name));
			}
			return $this->mappedData[$name];
		} elseif (isset($this->data[$name])) {
			return $this->data[$name];
		}
		return NULL;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function __set($name, $value) {
		if (property_exists($this, $name)) {
			$this->$name = $value;
		} elseif ($this->getDynamicPropertyType($name)) {

			if (is_string($value)) {
				unset($this->mappedData[$name]);
				$this->data[$name] = $value;
			} elseif ($value === NULL) {
				$this->mappedData[$name] = NULL;
				$this->data[$name] = NULL;
			} else {
				$this->mappedData[$name] = $value;
				if ($this->getDynamicPropertyType($name) === 'DateTime') {
					$this->data[$name] = $value->format('Y-m-d H:i:s.u');
				} else {
					$type = $this->getDynamicPropertyType($name);
					if (in_array($type, array('array', 'integer', 'float', 'boolean'))) {
						$this->data[$name] = $this->propertyMapper->convert($value, $type);
					} else {
						$this->data[$name] = $this->propertyMapper->convert($value, 'string');
					}
				}
			}
		} else {
			$this->data[$name] = $value;
		}
	}

	/**
	 * Get type of property from YAML configuration
	 *
	 * @param string $propertyName
	 * @return string
	 */
	protected function getDynamicPropertyType($propertyName) {
		if (array_key_exists($propertyName, $this->getModelConfiguration()) && !empty($this->modelConfiguration[$propertyName]['type'])) {
			return $this->modelConfiguration[$propertyName]['type'];
		} else {
			return NULL;
		}
	}

}

?>