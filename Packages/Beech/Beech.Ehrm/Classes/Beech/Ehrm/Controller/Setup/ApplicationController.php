<?php
namespace Beech\Ehrm\Controller\Setup;

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
class ApplicationController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\Ehrm\Helper\SettingsHelper
	 * @Flow\Inject
	 */
	protected $settingsHelper;

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
	 * @var \Beech\Ehrm\Domain\Repository\ApplicationRepository
	 * @Flow\Inject
	 */
	protected $applicationRepository;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$application = $this->applicationRepository->findApplication();
		$this->view->assign('locale', $application->getPreferences()->get('defaultLocale'));
		$this->view->assign('languages', $this->settingsHelper->getAvailableLanguages() );
	}

	/**
	 * @param string $locale
	 */
	public function updateAction($locale = 'EN_en') {
		$application = $this->applicationRepository->findApplication();
		$application->getPreferences()->set('defaultLocale', $locale);
		$this->applicationRepository->update($application);
		$this->redirect('index');
	}

}

?>