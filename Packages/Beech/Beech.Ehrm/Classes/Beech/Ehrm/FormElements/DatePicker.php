<?php
namespace Beech\Ehrm\FormElements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 13/02/13 10:04 AM
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
			if ($defaultValue === 'today') {
				$defaultValue = 'now';
			}
			$defaultValue = new \DateTime($defaultValue);
		}
		parent::setDefaultValue($defaultValue);
	}

}

?>