<?php
namespace Beech\Workflow\Validators\Property;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;;

/**
 * The NotEmptyValidator checks if a property of an entity is not empty
 * @ODM\EmbeddedDocument
 * @ODM\Document
 */
class EmptyValidator implements \Beech\Workflow\Core\ValidatorInterface {

	/**
	 * @var string
	 */
	protected $propertyName = '';

	/**
	 * @var object
	 */
	protected $targetEntity = NULL;

	/**
	 * Validate a property
	 *
	 * @return boolean
	 */
	public function isValid() {
		$propertyValue = $this->getPropertyValue();

		if ($propertyValue) {
			switch (gettype($propertyValue)) {
				case 'object':
					return (count((array) $propertyValue)) === 0;
				case 'array':
					return count($propertyValue) === 0;
				case 'string':
					return trim($propertyValue) === '';
				default:
						// default includes integer, boolean, double (yes, not float but double), resource, NULL, 'unknown type'
					return empty($propertyValue);
			}
		}

		return TRUE;
	}

	/**
	 * @param object $targetEntity
	 */
	public function setTargetEntity($targetEntity) {
		$this->targetEntity = $targetEntity;
	}

	/**
	 * @return object
	 */
	public function getTargetEntity() {
		return $this->targetEntity;
	}

	/**
	 * @param string $propertyName
	 */
	public function setPropertyName($propertyName) {
		$this->propertyName = $propertyName;
	}

	/**
	 * @return string
	 */
	public function getPropertyName() {
		return $this->propertyName;
	}

	/**
	 * Determine the value of the requested property of this entity
	 *
	 * @return mixed
	 */
	protected function getPropertyValue() {
		if (is_object($this->targetEntity)
			&& trim($this->propertyName) !== ''
			&& property_exists($this->targetEntity, $this->propertyName)) {

				// Get the property value from the target entity class
			$methodName = 'get' . ucfirst($this->propertyName);
			$propertyValue = $this->targetEntity->$methodName();
			return $propertyValue;
		}
		return FALSE;
	}
}

?>