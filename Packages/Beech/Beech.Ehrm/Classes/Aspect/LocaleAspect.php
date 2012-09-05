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
 * Setting locale for session
 *
 * @FLOW3\Aspect
 */
class LocaleAspect {

	/**
	 * @var \Beech\Ehrm\Frontend\Session
	 * @FLOW3\Inject
	 */
	protected $frontendSession;

	/**
	 * @var \TYPO3\FLOW3\Object\ObjectManagerInterface
	 * @FLOW3\Inject
	 */
	protected $objectManager;

	/**
	 * @var \TYPO3\FLOW3\Security\Context
	 * @FLOW3\Inject
	 */
	protected $securityContext;

	/**
	 * Setting default locale to session
	 *
	 * To overwrite default language add user name to settings:
	 * 	LanguageSetter:
	 * 	  user:
	 * 	    john: en
	 * 	    bram: nl
	 * 	    karol: pl
	 *
	 * @FLOW3\Before("method(TYPO3\Fluid\ViewHelpers\TranslateViewHelper->render())")
	 * @param \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint The current join point
	 * @return void
	 */
	public function addSessionLocaleToTranslateViewHelper(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
		$account = $this->securityContext->getAccount();
		if ($account instanceof \TYPO3\FLOW3\Security\Account) {
			$accountIdentifier = $this->securityContext->getAccount()->getAccountIdentifier();
			$userLanguageSetterCollection = $this->objectManager->getSettingsByPath(array('LanguageSetter', 'user'));
			if (isset($userLanguageSetterCollection[$accountIdentifier])) {
				$this->frontendSession->setCurrentLocale($userLanguageSetterCollection[$accountIdentifier]);
			}
		}
		if (!$this->frontendSession->isInitialized()) {
			$this->frontendSession->initialize();
		}
		$methodArguments = $joinPoint->getMethodArguments();
		if (empty($methodArguments['locale'])
			&& $this->frontendSession->getCurrentLocale() instanceof \TYPO3\FLOW3\I18n\Locale
		) {
			$joinPoint->setMethodArgument('locale', $this->frontendSession->getCurrentLocale()->getLanguage());
		}
	}
}

?>