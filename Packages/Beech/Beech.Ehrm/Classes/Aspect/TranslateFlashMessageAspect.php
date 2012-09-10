<?php
namespace Beech\Ehrm\Aspect;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 29-08-12 11:07
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use Doctrine\ORM\Mapping as ORM;

/**
 * Translation for flash message
 *
 * @FLOW3\Aspect
 */
class TranslateFlashMessageAspect {

	/**
	 * @var \TYPO3\FLOW3\I18n\Translator
	 * @FLOW3\Inject
	 */
	protected $translator;

	/**
	 * @param \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint
	 * @FLOW3\Before("method(Beech\.*\Controller\.*->addFlashMessage())")
	 * @return void
	 */
	public function translateFlashMessageOnAdd(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
		$joinPoint->setMethodArgument('messageBody', $this->translator->translateByOriginalLabel($joinPoint->getMethodArgument('messageBody')));
	}
}
?>