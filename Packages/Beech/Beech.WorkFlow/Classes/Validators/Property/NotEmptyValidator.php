<?php
namespace Beech\WorkFlow\Validators\Property;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Rens Admiraal <rens@beech.it>
 * Date: 27-08-12 22:13
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

class NotEmptyValidator implements \Beech\WorkFlow\Core\ValidatorInterface {

	/**
	 * @var string
	 */
	protected $propertyName;

	/**
	 * @return boolean
	 */
	public function isValid() {

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


}