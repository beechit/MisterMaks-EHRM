<?php
namespace Beech\Workflow\Validators\Property;

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