<?php
namespace Beech\Ehrm\Aspect;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 08-03-2013 13:02
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Aspect
 */
class CapitalizeSubpackageAspect {

	/**
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint
	 * @Flow\Before("method(TYPO3\Flow\Mvc\ActionRequest->setControllerSubpackageKey())")
	 * @return void
	 */
	public function makeSubpackageKeyUpperCase(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		$joinPoint->setMethodArgument('subpackageKey', ucfirst($joinPoint->getMethodArgument('subpackageKey')));
	}
}
?>
