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
	 * @var \TYPO3\FLOW3\Security\Context
	 * @FLOW3\Inject
	 */
	protected $securityContext;

	/**
	 * Added to support the addExampleAction()
	 *
	 * @var \Beech\Ehrm\Log\ApplicationLoggerInterface
	 * @FLOW3\Inject
	 */
	protected $applicationLogger;

	/**
	 * @FLOW3\Around("method(TYPO3\FLOW3\I18n\Configuration->getCurrentLocale())")
	 * @param \TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint The current join point
	 * @return \TYPO3\FLOW3\I18n\Locale
	 */
	public function overrideCurrentLocaleByUserSettings(\TYPO3\FLOW3\AOP\JoinPointInterface $joinPoint) {
		if ($this->securityContext->isInitialized() && $this->securityContext->getAccount() instanceof \TYPO3\FLOW3\Security\Account) {
			$person = $this->securityContext->getAccount()->getParty();
			if ($person instanceof \Beech\Party\Domain\Model\Person) {
				try {
					if ($person->getPreferences()->get('locale')) {
						return new \TYPO3\FLOW3\I18n\Locale($person->getPreferences()->get('locale'));
					}
				} catch (\Exception $exception) {
					$this->applicationLogger->log(sprintf(
						'Invalid locale identifier (%s) for person "%s" found',
						array($person->getPreferences()->get('locale'), $this->securityContext->getAccount()->getAccountIdentifier()))
					);
				};
			}
		}

		return $joinPoint->getAdviceChain()->proceed($joinPoint);
	}

}

?>