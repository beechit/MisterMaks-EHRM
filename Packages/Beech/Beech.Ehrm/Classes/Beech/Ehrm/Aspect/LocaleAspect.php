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
 * Get the locale from the user settings
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
	 * @var \Beech\Ehrm\Utility\PreferenceUtility
	 * @Flow\Inject
	 */
	protected $preferenceUtility;

	/**
	 * @Flow\Around("method(TYPO3\Flow\I18n\Configuration->getCurrentLocale())")
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint The current join point
	 * @return \TYPO3\Flow\I18n\Locale
	 */
	public function overrideCurrentLocaleByUserSettings(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		$localeIdentifier = $this->preferenceUtility->getApplicationPreference('locale');
		if (empty($localeIdentifier)) {
			$localeIdentifier = 'mul_ZZ';
		}
		try {
			return new \TYPO3\Flow\I18n\Locale($localeIdentifier);
		} catch (\Exception $exception) {
			if ($this->securityContext->isInitialized()) {
				$this->applicationLogger->log(sprintf(
					'Invalid locale identifier (%s) for person "%s" found',
					array($localeIdentifier, $this->securityContext->getAccount()->getAccountIdentifier()))
				);
			} else {
				$this->applicationLogger->log(sprintf(
						'Invalid locale identifier (%s) for application',
						array($localeIdentifier)
				));
			}
		};

		return $joinPoint->getAdviceChain()->proceed($joinPoint);
	}

}

?>