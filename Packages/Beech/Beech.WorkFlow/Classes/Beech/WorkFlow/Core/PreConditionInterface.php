<?php
namespace Beech\WorkFlow\Core;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 21:22
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 */
interface PreConditionInterface {

	/**
	 * Determine if a precondition is valid or completed
	 *
	 * @abstract
	 * @return boolean
	 */
	public function isMet();

}

?>