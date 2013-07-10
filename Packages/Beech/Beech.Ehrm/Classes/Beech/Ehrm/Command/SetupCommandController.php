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
	 * @var \Beech\CLA\Domain\Repository\JobPositionRepository
	 * @Flow\Inject
	 */
	protected $jobPositionRepository;

	/**
	 * @var \Doctrine\ODM\CouchDB\DocumentManager
	 */
	protected $documentManager;

	/**
	 * @var \Radmiraal\CouchDB\Persistence\DocumentManagerFactory
	 */
	protected $documentManagementFactory;

	/**
	 * @param \Radmiraal\CouchDB\Persistence\DocumentManagerFactory $documentManagerFactory
	 * @return void
	 */
	public function injectDocumentManagerFactory(\Radmiraal\CouchDB\Persistence\DocumentManagerFactory $documentManagerFactory) {
		$this->documentManagementFactory = $documentManagerFactory;
		$this->documentManager = $this->documentManagementFactory->create();
	}

	/**
	 * Create a company
	 *
	 * @param string $companyName Company name
	 * @return void
	 */
	public function initializeCommand($companyName) {

		if ($this->preferenceUtility->getApplicationPreference('company') !== NULL) {
			$this->outputLine('Application is already initialized');

		} elseif ($this->companyRepository->countByName($companyName) > 0) {
			$this->outputLine('Company "%s" already exists.', array($companyName));
			$this->quit(1);
		} else {

				// Create the company
			$company = new \Beech\Party\Domain\Model\Company();
			$company->setName($companyName);

			$this->companyRepository->add($company);

			$this->preferenceUtility->setApplicationPreference('company', $this->persistenceManager->getIdentifierByObject($company));

			$this->outputLine('Created an application for company "%s".', array($companyName));
		}

			// create top of job position hirarchy
		if ($this->jobPositionRepository->findOneByParentId('') === NULL) {

			$company = $this->companyRepository->findByIdentifier($this->preferenceUtility->getApplicationPreference('company'));

			if ($company !== NULL) {

				$jobPosition = new \Beech\CLA\Domain\Model\JobPosition();
				$jobPosition->setName('Owner');

				$jobPosition->setDepartment($company);
				$this->jobPositionRepository->add($jobPosition);

				$this->documentManager->flush();

				$this->outputLine('Created "%s" job position.', array($jobPosition->getName()));
			} else {
				$this->outputLine('Could not found main company!');
			}
		}
	}
}

?>