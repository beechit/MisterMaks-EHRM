<?php
namespace Beech\Ehrm\Administration\Controller;

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

/**
 * Setup controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class ApplicationSettingsController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\Ehrm\Helper\SettingsHelper
	 * @Flow\Inject
	 */
	protected $settingsHelper;

	/**
	 * @var \Beech\Ehrm\Helper\ThemeHelper
	 * @Flow\Inject
	 */
	protected $themeHelper;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 * @Flow\Inject
	 */
	protected $securityContext;

	/**
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
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 * @Flow\Inject
	 */
	protected $companyRepository;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {

		$themes = $this->themeHelper->getAvailableThemes();
		foreach ($themes as $name => $config) {
			$themes[$name] = $name;
		}

		$this->view->assignMultiple(array(
			'currentLocale' => $this->preferencesUtility->getApplicationPreference('locale', FALSE),
			'locales' => $this->settingsHelper->getAvailableLanguages(),
			'currentTheme' => $this->preferencesUtility->getApplicationPreference('theme', FALSE),
			'themes' => $themes
		));
	}

	/**
	 * @param string $locale
	 * @param string $theme
	 * @return void
	 */
	public function updateAction($locale = 'en_EN', $theme = 'Default') {
		$this->preferencesUtility->setApplicationPreference('locale', $locale);
		$this->preferencesUtility->setApplicationPreference('theme', $theme);
		$this->addFlashMessage('Application preferences updated');
		$this->redirect('index');
	}

	/**
	 * Environment wizard
	 */
	public function setupWizardAction() {

		$company = $this->companyRepository->findByIdentifier($this->preferencesUtility->getApplicationPreference('company'));

		$this->view->assign('company', $company);
		$this->view->assign('person', $this->getPerson());
	}

	/**
	 * Environment wizard completed
	 */
	public function setupWizardCompleteAction() {
		$this->preferencesUtility->setApplicationPreference('setupWizardComplete', TRUE);
		return TRUE;
	}

	/**
	 * Get current loggedin user
	 *
	 * @return \TYPO3\Party\Domain\Model\AbstractParty
	 * @throws \TYPO3\Flow\Security\Exception\AuthenticationRequiredException
	 */
	protected function getPerson() {

		if(!$this->securityContext->getAccount()) {
			throw new \TYPO3\Flow\Security\Exception\AuthenticationRequiredException();
		}

		return $this->securityContext->getAccount()->getParty();
	}
}

?>