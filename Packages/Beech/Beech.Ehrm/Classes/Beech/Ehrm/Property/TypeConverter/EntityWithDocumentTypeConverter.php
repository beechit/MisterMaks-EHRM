<?php
namespace Beech\Ehrm\Property\TypeConverter;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 1/3/13 5:04 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Entity with Document PersistentObjectConverter recognised YAML config
 *
 * @Flow\Scope("singleton")
 */
class EntityWithDocumentTypeConverter extends \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter {

	/**
	 * @var \Doctrine\ODM\CouchDB\DocumentManager
	 */
	protected $documentManager;

	/**
	 * @var \Radmiraal\CouchDB\Persistence\DocumentManagerFactory
	 */
	protected $documentManagementFactory;

	/**
	 * @var \TYPO3\Flow\Reflection\ReflectionService
	 */
	protected $reflectionService;

	/**
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * Model configuration as defined in YAML
	 *
	 * @var array
	 * @Flow\Transient
	 */
	protected $modelConfiguration;

	/**
	 * @var integer
	 */
	protected $priority = 99999;

	/**
	 * @param \TYPO3\Flow\Reflection\ReflectionService $reflectionService
	 * @return void
	 */
	public function injectReflectionService(\TYPO3\Flow\Reflection\ReflectionService $reflectionService) {
		$this->reflectionService = $reflectionService;
	}

	/**
	 * @param \Radmiraal\CouchDB\Persistence\DocumentManagerFactory $documentManagerFactory
	 * @return void
	 */
	public function injectDocumentManagerFactory(\Radmiraal\CouchDB\Persistence\DocumentManagerFactory $documentManagerFactory) {
		$this->documentManagementFactory = $documentManagerFactory;
		$this->documentManager = $this->documentManagementFactory->create();
	}

	/**
	 * We can only convert if the $targetType is either tagged with entity or value object.
	 *
	 * @param mixed $source
	 * @param string $targetType
	 * @return boolean
	 */
	public function canConvertFrom($source, $targetType) {
		return $this->reflectionService->isClassAnnotatedWith($targetType, 'Beech\Ehrm\Annotations\EntityWithDocument');
	}

	/**
	 * Convert an object from $source to an entity or a value object.
	 *
	 * @param mixed $source
	 * @param string $targetType
	 * @param array $convertedChildProperties
	 * @param \TYPO3\Flow\Property\PropertyMappingConfigurationInterface $configuration
	 * @return object the target type
	 * @throws \TYPO3\Flow\Property\Exception\InvalidTargetException
	 * @throws \InvalidArgumentException
	 */
	public function convertFrom($source, $targetType, array $convertedChildProperties = array(), \TYPO3\Flow\Property\PropertyMappingConfigurationInterface $configuration = NULL) {
		if (is_array($source)) {
			if ($this->reflectionService->isClassAnnotatedWith($targetType, 'TYPO3\Flow\Annotations\ValueObject')) {
					// Unset identity for valueobject to use constructor mapping, since the identity is determined from
					// constructor arguments
				unset($source['__identity']);
			}
			$object = $this->handleArrayData($source, $targetType, $convertedChildProperties, $configuration);
		} elseif (is_string($source)) {
			if ($source === '') {
				return NULL;
			}
			$object = $this->fetchObjectFromPersistence($source, $targetType);
		} else {
			throw new \InvalidArgumentException('Only strings and arrays are accepted.', 1305630314);
		}
		foreach ($convertedChildProperties as $propertyName => $propertyValue) {
			if (property_exists($object, $propertyName)) {
				\TYPO3\Flow\Reflection\ObjectAccess::setProperty($object, $propertyName, $propertyValue);
			} else {
				$object->__set($propertyName, $propertyValue);
			}
		}

		return $object;
	}

	/**
	 * The type of a property is determined by the reflection service.
	 *
	 * @param string $targetType
	 * @param string $propertyName
	 * @param \TYPO3\Flow\Property\PropertyMappingConfigurationInterface $configuration
	 * @return string
	 * @throws \TYPO3\Flow\Property\Exception\InvalidTargetException
	 */
	public function getTypeOfChildProperty($targetType, $propertyName, \TYPO3\Flow\Property\PropertyMappingConfigurationInterface $configuration) {
		$configuredTargetType = $configuration->getConfigurationFor($propertyName)->getConfigurationValue('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', self::CONFIGURATION_TARGET_TYPE);
		if ($configuredTargetType !== NULL) {
			return $configuredTargetType;
		}

		$varAnnotations = $this->reflectionService->getPropertyTagValues($targetType, $propertyName, 'var');

			// No @var annotation look if property exists in YAML
		if ($varAnnotations === array()) {
			$propertyType = $this->getDynamicPropertyType($targetType, $propertyName);
		} else {
			$propertyType = $varAnnotations[0];
		}

			// No property info, use string as default
		if ($propertyType === NULL) {
			return 'string';
		}

		$propertyInformation = \TYPO3\Flow\Utility\TypeHandling::parseType($propertyType);
		return $propertyInformation['type'] . ($propertyInformation['elementType'] !== NULL ? '<' . $propertyInformation['elementType'] . '>' : '');
	}

	/**
	 * Get configuration path for model description
	 *
	 * @return string
	 */
	protected function getModelConfigurationPath($targetType) {
		$className = preg_replace('/CouchDocument$/', '', $targetType);
		return str_replace(array('\\'), array('.'), $className);
	}

	/**
	 * Get model configuration
	 */
	protected function getModelConfiguration($targetType) {
		if ($this->modelConfiguration === NULL) {
			$this->modelConfiguration = array();
			$modelConfiguration = $this->configurationManager->getConfiguration('Models');
			if (array_key_exists($this->getModelConfigurationPath($targetType), $modelConfiguration)) {
				$this->modelConfiguration = $modelConfiguration[$this->getModelConfigurationPath($targetType)]['properties'];
			}
		}
		return $this->modelConfiguration;
	}

	/**
	 * Get type of property from YAML configuration
	 *
	 * @param string $targetType
	 * @param string $propertyName
	 * @return string
	 */
	protected function getDynamicPropertyType($targetType, $propertyName) {
		if (array_key_exists($propertyName, $this->getModelConfiguration($targetType)) && !empty($this->modelConfiguration[$propertyName]['type'])) {
			return $this->modelConfiguration[$propertyName]['type'];
		} else {
			return NULL;
		}
	}
}

?>