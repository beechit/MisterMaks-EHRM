<?php
namespace Beech\Workflow\Core;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 02-08-12 01:00
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 */
interface ActionInterface {

	const	STATUS_NEW = 0,
			STATUS_STARTED = 1,
			STATUS_FINISHED = 2,
			STATUS_EXPIRED = 3,
			STATUS_TERMINATED = 4;

	/**
	 * @return void
	 */
	public function dispatch();

	/**
	 * @param object $targetEntity
	 * @throws \Beech\Workflow\Exception\InvalidArgumentException
	 * @return void
	 */
	public function setTarget($targetEntity);

}
?>