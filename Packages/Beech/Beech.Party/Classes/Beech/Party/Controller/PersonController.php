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

use Beech\Absence\Domain\Model\Absence;
use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\Person as Person;

/**
 * Person controller for the Beech.Party package
 *
 * @Flow\Scope("singleton")
 */
class PersonController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\Person';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\PersonRepository';

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
	 * @var \Beech\Party\Domain\Repository\EducationRepository
	 * @Flow\Inject
	 */
	protected $educationRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\AssetRepository
	 * @Flow\Inject
	 */
	protected $assetRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\LicenceRepository
	 * @Flow\Inject
	 */
	protected $licenceRepository;

	/**
	 * @var \Beech\Absence\Domain\Repository\AbsenceRepository
	 * @Flow\Inject
	 */
	protected $absenceRepository;

	/**
	 * @var \Beech\CLA\Domain\Repository\ContractRepository
	 * @Flow\Inject
	 */
	protected $contractRepository;

	/**
	 * @var \Beech\Document\Domain\Repository\DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \Beech\Document\Domain\Repository\DocumentTypeRepository
	 * @Flow\Inject
	 */
	protected $documentTypeRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRelationRepository
	 * @Flow\Inject
	 */
	protected $personRelationRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 * @Flow\Inject
	 */
	protected $companyRepository;

	/**
	 * @var \Beech\Ehrm\Helper\SettingsHelper
	 * @Flow\Inject
	 */
	protected $settingsHelper;

	/**
	 * Shows a list of persons
	 *
	 * @return void
	 */
	public function listAction() {
		$persons = $this->repository->findAll();
		$this->view->assign('persons', $this->repository->findAll());
	}

	/**
	 * Shows a single person object
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @param boolean $withDetails
	 * @Flow\IgnoreValidation("$person")
	 * @return void
	 */
	public function showAction(Person $person = NULL, $withDetails = TRUE) {
		$this->view->assign('person', $person);
		$addresses = $this->addressRepository->findByParty($person->getId());
		$this->view->assign('addresses', $addresses);
		$phoneNumbers = $this->phoneNumberRepository->findByParty($person->getId());
		$this->view->assign('phoneNumbers', $phoneNumbers);
		$electronicAddresses = $this->electronicAddressRepository->findByParty($person->getId());
		$this->view->assign('electronicAddresses', $electronicAddresses);
		$bankAccounts = $this->bankAccountRepository->findByParty($person->getId());
		$this->view->assign('bankAccounts', $bankAccounts);
		$educations = $this->educationRepository->findByParty($person->getId());
		$this->view->assign('educations', $educations);
		$assets = $this->assetRepository->findByParty($person->getId());
		$this->view->assign('assets', $assets);
		$licences = $this->licenceRepository->findByParty($person->getId());
		$this->view->assign('licences', $licences);
		$absences = $this->absenceRepository->findActiveAbsence($person);
		$this->view->assign('absences', $absences);
		$this->view->assign('absence', is_array($absences) ? reset($absences):NULL);
		$contracts = $this->contractRepository->findByEmployee($person->getId());

			// need to be sorted, latest should be on the top
		\Beech\Ehrm\Utility\ObjectSorter::quickSort($contracts, 'creationDate', 'DESC');
		$this->view->assign('contracts', $contracts);

		// list of documents
		$this->view->assign('documentCategories', $this->documentTypeRepository->findAllGroupedByCategories());
		$this->view->assign('documents', $this->documentRepository->findByParty($person));

		$this->view->assign('persons', $this->repository->findAll());
		$this->view->assign('personRelations', $this->personRelationRepository->findByPersonRelatedTo($person));
	}

	/**
	 * Edit a single person object
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @param boolean $withDetails
	 * @Flow\IgnoreValidation("$person")
	 * @return void
	 */
	public function editAction(Person $person = NULL, $withDetails = TRUE) {
		$this->view->assign('person', $person);
		$addresses = $this->addressRepository->findByParty($person->getId());
		$this->view->assign('addresses', $addresses);
		$phoneNumbers = $this->phoneNumberRepository->findByParty($person->getId());
		$this->view->assign('phoneNumbers', $phoneNumbers);
		$electronicAddresses = $this->electronicAddressRepository->findByParty($person->getId());
		$this->view->assign('electronicAddresses', $electronicAddresses);
		$bankAccounts = $this->bankAccountRepository->findByParty($person->getId());
		$this->view->assign('bankAccounts', $bankAccounts);
		$educations = $this->educationRepository->findByParty($person->getId());
		$this->view->assign('educations', $educations);
		$assets = $this->assetRepository->findByParty($person->getId());
		$this->view->assign('assets', $assets);
		$licences = $this->licenceRepository->findByParty($person->getId());
		$this->view->assign('licences', $licences);
		$absences = $this->absenceRepository->findByParty($person->getId());
		$this->view->assign('absences', $absences);
		$this->view->assign('languages', $this->settingsHelper->getAvailableLanguages());
		$this->view->assign('departments', $this->companyRepository->findAll());

		$this->view->assign('withDetails', $withDetails);
	}

	/**
	 * Shows a wizard for creating a new person object
	 *
	 * @return void
	 */
	public function newAction() {
		$this->view->assign('languages', $this->settingsHelper->getAvailableLanguages());
		$this->view->assign('departments', $this->companyRepository->findAll());
		//$this->view->assign('documentCategories', $this->documentTypeRepository->findAllGroupedByCategories());
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to add
	 * @return void
	 */
	public function createAction(Person $person = NULL) {
		$this->repository->add($person);

		$this->addFlashMessage('Person is added');

		if ($this->request->hasArgument('noEmberRedirect')) {
			$options = array('person' => $person, 'withDetails' => FALSE);
			if ($this->request->hasArgument('withDetails')) {
				$options['withDetails'] = $this->request->getArgument('withDetails');
			}
			$this->redirect('edit', NULL, NULL, $options);
		} else {
			$this->emberRedirect('#/person');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to update
	 * @return void
	 */
	public function updateAction(Person $person = NULL) {
		$this->repository->update($person);
		$this->addFlashMessage('Person is updated');
		if ($this->request->hasArgument('noEmberRedirect')) {
			$options = array('person' => $person, 'withDetails' => FALSE);
			if ($this->request->hasArgument('withDetails')) {
				$options['withDetails'] = $this->request->getArgument('withDetails');
			}
			$this->redirect('edit', NULL, NULL, $options);
		} else {
			$this->emberRedirect('#/person');
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to delete
	 * @Flow\IgnoreValidation("$person")
	 * @return void
	 */
	public function deleteAction(Person $person = NULL) {
		$this->repository->remove($person);
			// persist manualy because GET requests will not be auto persisted
		$this->persistenceManager->persistAll();
		$this->addFlashMessage('Person is removed');
		$this->emberRedirect('#/person');
	}

}

?>
