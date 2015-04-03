<?php
namespace Beech\Ehrm\Form\Elements;

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
 * A date picker form element
 */
class DatePicker extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * @return void
	 */
	public function initializeFormElement() {
		$this->setDataType('DateTime');
	}

	/**
	 * Set the default value of the element
	 *
	 * @param mixed $defaultValue
	 * @return void
	 */
	public function setDefaultValue($defaultValue) {
		if (!$defaultValue instanceof \DateTime) {
				// TODO: remove 'today' and keep 'now' for this context
			if ($defaultValue === 'now') {
			}
			$defaultValue = new \DateTime($defaultValue);
		}
		parent::setDefaultValue($defaultValue);
	}

}

?>