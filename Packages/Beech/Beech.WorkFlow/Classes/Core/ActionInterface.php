<?php
namespace Beech\WorkFlow\Core;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Rens Admiraal <rens@beech.it>
 * Date: 02-08-12 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

/**
 */
interface ActionInterface {

	const	STATUS_NEW = 0,
			STATUS_STARTED = 1,
			STATUS_FINISHED = 2,
			STATUS_EXPIRED = 3,
			STATUS_TERMINATED = 4,
			PATTERN_TARGET_NAME = '#^(\\\[A-Z]{1}[a-zA-Z0-9\_]*){1,}:[a-g0-9\-]{36}$#';

	/**
	 * @return void
	 */
	public function start();

	/**
	 * @param string $target
	 * @return void
	 */
	public function setTarget($target);

}

?>