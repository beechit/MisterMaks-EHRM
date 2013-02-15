<?php
namespace Beech\Task\Domain\Factory;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-08-12 10:24
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Task\Domain\Model\Priority;

/**
 * @Flow\Scope("singleton")
 */
class PriorityFactory {

	/**
	 * This function creates a model object for the task model.
	 *
	 * @param string $label
	 * @return \Beech\Task\Domain\Model\Priority
	 */
	public static function createPriority($label) {
		$priority = new Priority();
		$priority->setLabel($label);
		return $priority;
	}

}

?>