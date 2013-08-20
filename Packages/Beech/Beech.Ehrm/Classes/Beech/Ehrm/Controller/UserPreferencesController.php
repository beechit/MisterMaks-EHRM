<?php
namespace Beech\Ehrm\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 02-09-12 01:16
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Exception\AuthenticationRequiredException;

/**
 * Setup controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class UserPreferencesController extends AbstractController {

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
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('locale', $this->getPerson()->getPreferences()->get('locale'));
		$this->view->assign('languages', $this->settingsHelper->getAvailableLanguages());
	}

	/**
	 * Update action
	 *
	 * @param string $locale
	 * @return void
	 */
	public function updateAction($locale = 'en_EN') {

		$this->getPerson()->getPreferences()->set('locale', $locale);
		$this->personRepository->update($this->getPerson());

		$this->addFlashMessage('User preferences updated');
		$this->redirect('index');
	}

	/**
	 * Get current loggedin user
	 *
	 * @return \TYPO3\Party\Domain\Model\Person
	 * @throws \TYPO3\Flow\Security\Exception\AccessDeniedException
	 */
	protected function getPerson() {

		if(!$this->securityContext->getAccount()) {
			throw new AuthenticationRequiredException();
		}

		return $this->securityContext->getAccount()->getParty();
	}

}

?>