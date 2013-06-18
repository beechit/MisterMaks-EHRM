<?php
namespace Beech\Workflow\Validators\Property;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * The EqualsValidator checks if a property of an entity the same as the value
 */
class EqualsValidator implements \Beech\Workflow\Core\ValidatorInterface {

	/**
	 * @var string
	 */
	protected $property;

	/**
	 * @var mixed
	 */
	protected $value;

	/**
	 * Validate a property
	 *
	 * @return boolean
	 */
	public function isValid() {

		return ($this->property === $this->value);
	}

	/**
	 * Set property
	 *
	 * @param string $property
	 */
	public function setProperty($property) {
		$this->property = $property;
	}

	/**
	 * Set value
	 *
	 * @param mixed $value
	 */
	public function setValue($value) {
		$this->value = $value;
	}

}

?>
