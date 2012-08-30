<?php
namespace Beech\WorkFlow\Core;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 22:11
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 */
interface ValidatorInterface {

	/**
	 * @abstract
	 * @return mixed
	 */
	public function isValid();

}

?>