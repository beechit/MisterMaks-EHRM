<?php
namespace Beech\Workflow\Validators\Property;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * The NotEmptyValidator checks if a property of an entity is not empty
 */
class EmptyValidator implements \Beech\Workflow\Core\ValidatorInterface {

	/**
	 * @var mixed
	 */
	protected $property;

	/**
	 * Validate a property
	 *
	 * @return boolean
	 */
	public function isValid() {

		if ($this->property) {
			switch (gettype($this->property)) {
				case 'object':
					return (count((array) $this->property)) === 0;
				case 'array':
					return count($this->property) === 0;
				case 'string':
					return trim($this->property) === '';
				default:
						// default includes integer, boolean, double (yes, not float but double), resource, NULL, 'unknown type'
					return empty($this->property);
			}
		}

		return TRUE;
	}

	/**
	 * @param mixed $property
	 */
	public function setProperty($property) {
		$this->property = $property;
	}
}
