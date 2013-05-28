<?php
namespace Beech\Ehrm\Aspect;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 29-05-13 11:07
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @Flow\Aspect
 */
class OverrideDefaultReferrerAspect {

	/**
	 * @Flow\Around("method(TYPO3\Fluid\ViewHelpers\FormViewHelper->renderHiddenReferrerFields())")
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint The current join point
	 */
	public function overrideCurrentLocaleByUserSettings(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		//return '';
		return $joinPoint->getAdviceChain()->proceed($joinPoint);
	}

}

?>