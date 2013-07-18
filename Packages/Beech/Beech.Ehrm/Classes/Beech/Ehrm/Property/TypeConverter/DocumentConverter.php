<?php
namespace Beech\Ehrm\Property\TypeConverter;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 1/3/13 5:04 PM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Document PersistentObjectConverter recognised YAML config
 *
 * @Flow\Scope("singleton")
 */
class DocumentConverter extends \Beech\Ehrm\Property\TypeConverter\EntityWithDocumentTypeConverter {

	/**
	 * @var string
	 */
	const PATTERN_MATCH_UUID = '/[a-zA-Z_-0-9]/';

	/**
	 * @var integer
	 */
	protected $priority = 10;

	/**
	 * We can only convert if the $targetType is either tagged with entity or value object.
	 *
	 * @param mixed $source
	 * @param string $targetType
	 * @return boolean
	 */
	public function canConvertFrom($source, $targetType) {
		return $this->reflectionService->isClassAnnotatedWith($targetType, 'Doctrine\ODM\CouchDB\Mapping\Annotations\Document');
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
			if ($this->reflectionService->isPropertyAnnotatedWith($targetType, $propertyName, 'Doctrine\ODM\CouchDB\Mapping\Annotations\Attachments')) {
				$attachments = array();
				foreach ($propertyValue as $version => $value) {
					$safeFileName = preg_replace('/[^a-zA-Z-_0-9\.]*/', '', $value['name']);
					if (!isset($value['type'])) {
						$value['type'] = \TYPO3\Flow\Utility\MediaTypes::getMediaTypeFromFilename($safeFileName);
					}
					$attachments[$safeFileName] = \Doctrine\CouchDB\Attachment::createFromBinaryData(\TYPO3\Flow\Utility\Files::getFileContents($value['tmp_name']), $value['type']);
				}
				$propertyValue = $attachments;
			}

			$result = \TYPO3\Flow\Reflection\ObjectAccess::setProperty($object, $propertyName, $propertyValue);
			if ($result === FALSE) {
				$exceptionMessage = sprintf(
					'Property "%s" having a value of type "%s" could not be set in target object of type "%s". Make sure that the property is accessible properly, for example via an appropriate setter method.',
					$propertyName,
					(is_object($propertyValue) ? get_class($propertyValue) : gettype($propertyValue)),
					$targetType
				);
				throw new \TYPO3\Flow\Property\Exception\InvalidTargetException($exceptionMessage, 1297935345);
			}
		}

		return $object;
	}


	/**
	 * Fetch an object from persistence layer.
	 *
	 * @param string $identity
	 * @param string $targetType
	 * @return object
	 * @throws \TYPO3\Flow\Property\Exception\TargetNotFoundException
	 * @throws \TYPO3\Flow\Property\Exception\InvalidSourceException
	 */
	protected function fetchObjectFromPersistence($identity, $targetType) {
		if (is_string($identity)) {
			$object = $this->documentManager->find($targetType, $identity);
		} else {
			throw new \TYPO3\Flow\Property\Exception\InvalidSourceException('The identity property "' . $identity . '" is not a string.', 1356681336);
		}

		if ($object === NULL) {
			throw new \TYPO3\Flow\Property\Exception\TargetNotFoundException('Document with identity "' . print_r($identity, TRUE) . '" not found.', 1356681356);
		}

		return $object;
	}

}

?>