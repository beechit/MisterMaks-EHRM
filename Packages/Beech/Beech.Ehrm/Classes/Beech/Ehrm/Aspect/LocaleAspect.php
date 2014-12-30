<?php
namespace Beech\Ehrm\Aspect;

/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * @Flow\Around("method(TYPO3\Flow\I18n\Configuration->getCurrentLocale())")
	 * @param \TYPO3\Flow\Aop\JoinPointInterface $joinPoint The current join point
	 * @return \TYPO3\Flow\I18n\Locale
	 */
	public function overrideCurrentLocaleByUserSettings(\TYPO3\Flow\Aop\JoinPointInterface $joinPoint) {
		$localeIdentifier = $this->preferencesUtility->getApplicationPreference('locale');
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