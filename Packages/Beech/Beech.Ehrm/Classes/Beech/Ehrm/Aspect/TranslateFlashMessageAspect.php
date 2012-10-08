<?php
namespace Beech\Ehrm\Aspect;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 29-08-12 11:07
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * Translation for flash message
 *
 * @Flow\Aspect
 */
class TranslateFlashMessageAspect {

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint
	 * @Flow\Before("method(Beech\.*\Controller\.*->addFlashMessage())")
	 * @return void
	 */
	public function translateFlashMessageOnAdd(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		$joinPoint->setMethodArgument('messageBody', $this->translator->translateByOriginalLabel($joinPoint->getMethodArgument('messageBody')));
	}
}
?>