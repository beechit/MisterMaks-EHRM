<?php
namespace Beech\Ehrm\Controller;

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
class SetupController extends AbstractController {

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
	 * @var \TYPO3\Flow\Security\AccountRepository
	 * @Flow\Inject
	 */
	protected $accountRepository;

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
		if ($this->securityContext->isInitialized()) {
			$this->view->assign('locale', $this->securityContext->getAccount()->getParty()->getPreferences()->get('locale'));
		}
		$this->view->assign('languages', $this->settingsHelper->getAvailableLanguages() );
	}

	/**
	 * Update action
	 *
	 * @param string $locale
	 * @return void
	 */
	public function updateAction($locale = 'EN_en') {
		$accountIdentifier = $this->securityContext->getAccount()->getAccountIdentifier();
		$account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($accountIdentifier, 'DefaultProvider');
		if ($account->getParty() instanceof \Beech\Party\Domain\Model\Person) {
				$account->getParty()->getPreferences()->set('locale', $locale);
				$this->accountRepository->update($account);
				$this->personRepository->update($account->getParty());
		}
		$this->redirect('index');
	}

}

?>