<?php
namespace Beech\WorkFlow\Core;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 27-08-12 21:20
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 */
interface OutputHandlerInterface {

	/**
	 * Execute the output handler class
	 *
	 * @abstract
	 * @return mixed
	 */
	public function invoke();

}

?>