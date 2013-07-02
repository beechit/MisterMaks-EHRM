<?php
namespace Beech\Party\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
	 * @Flow\IgnoreValidation("$person")
	 * @return void
	 */
	public function showAction(Person $person) {
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
	}

	/**
	 * Edit a single person object
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @Flow\IgnoreValidation("$person")
	 * @return void
	 */
	public function editAction(Person $person) {
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
	}

	/**
	 * Shows a form for creating a new account object
	 *
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to add
	 * @return void
	 */
	public function createAction(Person $person) {
		$this->repository->add($person);

		$this->addFlashMessage('Person is added');
		$this->emberRedirect('#/person');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to update
	 * @return void
	 */
	public function updateAction(Person $person) {
		$this->repository->update($person);
		$this->addFlashMessage('Person is updated');
		$this->emberRedirect('#/person');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to delete
	 * @Flow\IgnoreValidation("$person")
	 * @return void
	 */
	public function deleteAction(Person $person) {
		$this->repository->remove($person);
			// persist manualy because GET requests will not be auto persisted
		$this->persistenceManager->persistAll();
		$this->addFlashMessage('Person is removed');
		$this->emberRedirect('#/person');
	}

}

?>