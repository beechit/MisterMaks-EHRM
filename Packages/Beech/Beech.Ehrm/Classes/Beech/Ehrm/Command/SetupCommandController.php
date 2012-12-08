<?php
namespace Beech\Ehrm\Command;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 19-09-12 11:52
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Setup command controller for the Beech.Ehrm package
 *
 * @Flow\Scope("singleton")
 */
class SetupCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

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
	 * Create a company
	 *
	 * @param string $companyName Company name
	 * @return void
	 */
	public function initializeCommand($companyName) {
		if ($this->preferenceUtility->getApplicationPreference('company') !== NULL) {
			$this->outputLine('Application is already initialized');
			$this->quit(1);
		}
		if ($this->companyRepository->countByName($companyName) > 0) {
			$this->outputLine('Company "%s" already exists.', array($companyName));
			$this->quit(1);
		}

			// Create the company
		$company = new \Beech\Party\Domain\Model\Company();
		$company->setName($companyName);
		$company->setChamberOfCommerceNumber('');

		$this->companyRepository->add($company);

		$this->preferenceUtility->setApplicationPreference('company', $this->persistenceManager->getIdentifierByObject($company));

		$this->outputLine('Created an application for company "%s".', array($companyName));
	}
}
?>