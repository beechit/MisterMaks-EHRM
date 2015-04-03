<?php
namespace Beech\Party\Controller;

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
use Beech\Party\Domain\Model\Company as Company;

/**
 * Company controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class CompanyController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\Company';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\CompanyRepository';

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @var \Beech\Party\Domain\Repository\AddressRepository
	 * @Flow\Inject
	 */
	protected $addressRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PhoneNumberRepository
	 * @Flow\Inject
	 */
	protected $phoneNumberRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\ElectronicAddressRepository
	 * @Flow\Inject
	 */
	protected $electronicAddressRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\BankAccountRepository
	 * @Flow\Inject
	 */
	protected $bankAccountRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * Shows company preview with departments
	 *
	 * @return void
	 */
	public function indexAction(Company $company = NULL) {
		$this->view->assign('companies', $this->repository->findAll());
			// take initial company as a default
		if ($company === NULL) {
			$initialCompanyIdentifier = $this->preferencesUtility->getApplicationPreference('company');
			$company = $this->repository->findByIdentifier($initialCompanyIdentifier);
		}
		$this->view->assign('company', $company);
	}

	/**
	 * Shows a list of companies
	 *
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('companies', $this->repository->findAll());
	}

	/**
	 * Shows a single company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company
	 * @param boolean $withDetails
	 * @Flow\IgnoreValidation("$company")
	 * @return void
	 */
	public function showAction(Company $company = NULL, $withDetails = TRUE) {
		$this->view->assign('company', $company);
		$addresses = $this->addressRepository->findByParty($company->getId());
		$this->view->assign('addresses', $addresses);
		$phoneNumbers = $this->phoneNumberRepository->findByParty($company->getId());
		$this->view->assign('phoneNumbers', $phoneNumbers);
		$electronicAddresses = $this->electronicAddressRepository->findByParty($company->getId());
		$this->view->assign('electronicAddresses', $electronicAddresses);
		$bankAccounts = $this->bankAccountRepository->findByParty($company->getId());
		$this->view->assign('bankAccounts', $bankAccounts);
		$this->view->assign('withDetails', $withDetails);
	}

	/**
	 * Edit a single company object
	 *
	 * @param \Beech\Party\Domain\Model\Company $company
	 * @param boolean $withDetails
	 * @Flow\IgnoreValidation("$company")
	 * @return void
	 */
	public function editAction(Company $company = NULL, $withDetails = TRUE) {
		$this->view->assign('company', $company);
		$addresses = $this->addressRepository->findByParty($company->getId());
		$this->view->assign('addresses', $addresses);
		$phoneNumbers = $this->phoneNumberRepository->findByParty($company->getId());
		$this->view->assign('phoneNumbers', $phoneNumbers);
		$electronicAddresses = $this->electronicAddressRepository->findByParty($company->getId());
		$this->view->assign('electronicAddresses', $electronicAddresses);
		$this->view->assign('companies', $this->repository->findAllowedParentsFor($company));
		$this->view->assign('withDetails', $withDetails);
		$persons = $this->personRepository->findAll();
		$this->view->assign('persons', $persons);
	}

	/**
	 * Shows a form for creating a new account object
	 *
	 * @return void
	 */
	public function newAction() {
		$this->view->assign('companies', $this->repository->findAll());
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $company A new company to add
	 *
	 * @return void
	 */
	public function createAction(Company $company = NULL) {
		$this->repository->add($company);
		$this->addFlashMessage('Company is added');
		if ($this->request->hasArgument('noEmberRedirect')) {
			$options = array('company' => $company);
			if ($this->request->hasArgument('withDetails')) {
				$options['withDetails'] = $this->request->getArgument('withDetails');
			}
			$this->redirect('edit', NULL, NULL, $options);
		} else {
			$this->emberRedirect('#/company');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $company A new company to update
	 *
	 * @return void
	 */
	public function updateAction(Company $company = NULL) {
		$this->repository->update($company);
		$this->addFlashMessage('Company is updated');
		$this->emberRedirect('#/company');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Company $company A new company to delete
	 * @return void
	 */
	public function deleteAction(Company $company = NULL) {
		$this->repository->remove($company);
			// persist manualy because GET requests will not be auto persisted
		$this->persistenceManager->persistAll();
		$this->addFlashMessage('Company is removed');
		$this->emberRedirect('#/company');
	}

}

?>
