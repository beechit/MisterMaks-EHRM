<?php
namespace Beech\Ehrm\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 02-09-12 01:16
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
	 * @var \Beech\Ehrm\Utility\PreferenceUtility
	 * @Flow\Inject
	 */
	protected $preferenceUtility;

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
			'currentLocale' => $this->preferenceUtility->getApplicationPreference('locale', FALSE),
			'locales' => $this->settingsHelper->getAvailableLanguages(),
			'currentTheme' => $this->preferenceUtility->getApplicationPreference('theme', FALSE),
			'themes' => $themes
		));
	}

	/**
	 * @param string $locale
	 * @param string $theme
	 * @return void
	 */
	public function updateAction($locale = 'en_EN', $theme = 'Default') {
		$this->preferenceUtility->setApplicationPreference('locale', $locale);
		$this->preferenceUtility->setApplicationPreference('theme', $theme);
		$this->addFlashMessage('Application preferences updated');
		$this->redirect('index');
	}

	/**
	 * Environment wizard
	 */
	public function setupWizardAction() {

		$company = $this->companyRepository->findByIdentifier($this->preferenceUtility->getApplicationPreference('company'));

		$this->view->assign('company', $company);
		$this->view->assign('person', $this->getPerson());
	}

	/**
	 * Environment wizard completed
	 */
	public function setupWizardCompleteAction() {
		$this->preferenceUtility->setApplicationPreference('setupWizardComplete', TRUE);
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