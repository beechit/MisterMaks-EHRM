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
 * Setting locale for session
 *
 * @Flow\Aspect
 */
class LocaleAspect {

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
	 * Added to support the addExampleAction()
	 *
	 * @var \Beech\Ehrm\Log\ApplicationLoggerInterface
	 * @Flow\Inject
	 */
	protected $applicationLogger;

	/**
	 * @var \Beech\Ehrm\Domain\Repository\ApplicationRepository
	 * @Flow\Inject
	 */
	protected $applicationRepository;

	/**
	 * @Flow\Around("method(TYPO3\Flow\I18n\Configuration->getCurrentLocale())")
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint The current join point
	 * @return \TYPO3\Flow\I18n\Locale
	 */
	public function overrideCurrentLocaleByUserSettings(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		if ($this->securityContext->isInitialized() && $this->securityContext->getAccount() instanceof \TYPO3\Flow\Security\Account) {
			$person = $this->securityContext->getAccount()->getParty();
			if ($person instanceof \Beech\Party\Domain\Model\Person) {
				try {
						// User specific setting found
					if ($person->getPreferences()->get('locale')) {
						return new \TYPO3\Flow\I18n\Locale($person->getPreferences()->get('locale'));
					}
				} catch (\Exception $exception) {
					$this->applicationLogger->log(sprintf(
						'Invalid locale identifier (%s) for person "%s" found',
						array($person->getPreferences()->get('locale'), $this->securityContext->getAccount()->getAccountIdentifier()))
					);
				};
			}
		}

			// Us the global settings
		$application = $this->applicationRepository->findApplication();
		if ($application instanceof \Beech\Ehrm\Domain\Model\Application) {
			$defaultLocale = $application->getPreferences()->get('locale');
			if ($defaultLocale !== NULL) {
				return new \TYPO3\Flow\I18n\Locale($defaultLocale);
			}
		}
		return $joinPoint->getAdviceChain()->proceed($joinPoint);
	}

}

?>