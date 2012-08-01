<?php
namespace Beech\WorkFlow\Core;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Developer: Rens Admiraal <rens@beech.it>
 * Date: 27-08-12 21:22
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;

interface PreConditionInterface {

	public function isMet();

}

?>