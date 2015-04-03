<?php
namespace Beech\Ehrm\Property\TypeConverter;

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
					if (!isset($value['name'])) {
						break;
					}
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