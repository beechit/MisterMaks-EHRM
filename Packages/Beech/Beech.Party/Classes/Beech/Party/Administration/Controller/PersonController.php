<?php
namespace Beech\Party\Administration\Controller;

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
	 * Shows a list of persons
	 *
	 * @return void
	 */
	public function listAction() {
		$persons = $this->repository->findAll();
		foreach ($persons as $person) {
			$identifier = $this->persistenceManager->getIdentifierByObject($person);
			$person->id = $identifier;
		}
		$this->view->assign('persons', $this->repository->findAll());
	}

	/**
	 * Shows a single person object
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function showAction(Person $person) {
		$this->view->assign('person', $person);
	}

	/**
	 * Edit a single person object
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function editAction(Person $person) {
		$identifier = $this->persistenceManager->getIdentifierByObject($person);
		$person->id = $identifier;
		$this->view->assign('person', $person);
		$addresses = $this->addressRepository->findByParty($identifier);
		$this->view->assign('addresses', $addresses);
		$phoneNumbers = $this->phoneNumberRepository->findByParty($identifier);
		$this->view->assign('phoneNumbers', $phoneNumbers);
		$electronicAddresses = $this->electronicAddressRepository->findByParty($identifier);
        $this->view->assign('electronicAddresses', $electronicAddresses);
		$bankAccounts = $this->bankAccountRepository->findByParty($identifier);
		$this->view->assign('bankAccounts', $bankAccounts);
		$educations = $this->educationRepository->findByParty($identifier);
		$this->view->assign('educations', $educations);
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
	 *
	 * @return void
	 */
	public function createAction(Person $person) {
		$this->repository->add($person);
		$this->addFlashMessage('Person is added');
		$this->redirect('list');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to update
	 *
	 * @return void
	 */
	public function updateAction(Person $person) {
		$this->repository->update($person);
		$this->addFlashMessage('Person is updated.');
		$this->redirect('list');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to delete
	 *
	 * @return void
	 */
	public function deleteAction(Person $person) {
		$this->repository->remove($person);
		$this->addFlashMessage('Person is removed .');
		$this->redirect('list');
	}

	/**
	 * @param \Beech\Party\Domain\Model\Address $address A new address to add
	 *
	 * @return void
	 */
	public function addAddressAction(\Beech\Party\Domain\Model\Address $address) {
		$address->setParty($this->persistenceManager->getIdentifierByObject($address->getParty()));
		$this->addressRepository->add($address);
		$this->addFlashMessage('Added.');
		$this->redirect('edit', NULL, NULL, array('person' => $address->getParty()));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Address $address A new address to update
	 *
	 * @return void
	 */
	public function updateAddressAction(\Beech\Party\Domain\Model\Address $address) {
		$address->setParty($this->persistenceManager->getIdentifierByObject($address->getParty()));
		$this->addressRepository->update($address);
		$this->addFlashMessage('Update address.');
		$this->redirect('edit', NULL, NULL, array('person' => $address->getParty()));
	}

	/**
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A new phoneNumber to add
	 *
	 * @return void
	 */
	public function addPhoneNumberAction(\Beech\Party\Domain\Model\PhoneNumber $phoneNumber) {
		$phoneNumber->setParty($this->persistenceManager->getIdentifierByObject($phoneNumber->getParty()));
		$this->phoneNumberRepository->add($phoneNumber);
		$this->addFlashMessage('Added.');
		$this->redirect('edit', NULL, NULL, array('person' => $phoneNumber->getParty()));
	}

	/**
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A  phoneNumber to update
	 *
	 * @return void
	 */
	public function updatePhoneNumberAction(\Beech\Party\Domain\Model\PhoneNumber $phoneNumber) {
		$phoneNumber->setParty($this->persistenceManager->getIdentifierByObject($phoneNumber->getParty()));
		$this->phoneNumberRepository->update($phoneNumber);
		$this->addFlashMessage('Updated.');
		$this->redirect('edit', NULL, NULL, array('person' => $phoneNumber->getParty()));
	}


	/**
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A new phoneNumber to remove
	 *
	 * @return void
	 */
	public function removePhoneNumberAction(\Beech\Party\Domain\Model\PhoneNumber $phoneNumber) {
		$person = $phoneNumber->getParty();
		$phoneNumber->setParty(NULL);
		$this->phoneNumberRepository->update($phoneNumber);
		$this->addFlashMessage('Removed.');
		$this->redirect('edit', NULL, NULL, array('person' => $person));
	}

	/**
	* @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress A new electronicAddress to add
	*
	* @return void
	*/
	public function addElectronicAddressAction(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		$electronicAddress->setParty($this->persistenceManager->getIdentifierByObject($electronicAddress->getParty()));
		$this->electronicAddressRepository->add($electronicAddress);
		$this->addFlashMessage('Added.');
		$this->redirect('edit', NULL, NULL, array('person' => $electronicAddress->getParty()));
	}

	/**
	* @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress A  electronicAddress to update
	*
	* @return void
	*/
	public function updateElectronicAddressAction(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		$electronicAddress->setParty($this->persistenceManager->getIdentifierByObject($electronicAddress->getParty()));
		$this->electronicAddressRepository->update($electronicAddress);
		$this->addFlashMessage('Updated.');
		$this->redirect('edit', NULL, NULL, array('person' => $electronicAddress->getParty()));
	}

	/**
	* @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddress A new electronicAddress to remove
	*
	* @return void
	*/
	public function removeElectronicAddressAction(\Beech\Party\Domain\Model\ElectronicAddress $electronicAddress) {
		$person = $electronicAddress->getParty();
		$electronicAddress->setParty(NULL);
		$this->electronicAddressRepository->update($electronicAddress);
		$this->addFlashMessage('Removed.');
		$this->redirect('edit', NULL, NULL, array('person' => $person));
	}

	/**
	 * @param \Beech\Party\Domain\Model\BankAccount $bankAccount A new bankAccount to add
	 *
	 * @return void
	 */
	public function addBankAccountAction(\Beech\Party\Domain\Model\BankAccount $bankAccount) {
		$bankAccount->setParty($this->persistenceManager->getIdentifierByObject($bankAccount->getParty()));
		$this->bankAccountRepository->add($bankAccount);
		$this->addFlashMessage('Added.');
		$this->redirect('edit', NULL, NULL, array('person' => $bankAccount->getParty()));
	}

	/**
	 * @param \Beech\Party\Domain\Model\BankAccount $bankAccount A  bankAccount to update
	 *
	 * @return void
	 */
	public function updateBankAccountAction(\Beech\Party\Domain\Model\BankAccount $bankAccount) {
		$bankAccount->setParty($this->persistenceManager->getIdentifierByObject($bankAccount->getParty()));
		$this->bankAccountRepository->update($bankAccount);
		$this->addFlashMessage('Updated.');
		$this->redirect('edit', NULL, NULL, array('person' => $bankAccount->getParty()));
	}

	/**
	 * @param \Beech\Party\Domain\Model\BankAccount $bankAccount A new bankAccount to remove
	 *
	 * @return void
	 */
	public function removeBankAccountAction(\Beech\Party\Domain\Model\BankAccount $bankAccount) {
		$person = $bankAccount->getParty();
		$bankAccount->setParty(NULL);
		$this->bankAccountRepository->update($bankAccount);
		$this->addFlashMessage('Removed.');
		$this->redirect('edit', NULL, NULL, array('person' => $person));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Education $Education A new education to add
	 *
	 * @return void
	 */
	public function addEducationAction(\Beech\Party\Domain\Model\Education $education) {
		$education->setParty($this->persistenceManager->getIdentifierByObject($education->getParty()));
		$this->educationRepository->add($education);
		$this->addFlashMessage('Added.');
		$this->redirect('edit', NULL, NULL, array('person' => $education->getParty()));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Education $education A  education to update
	 *
	 * @return void
	 */
	public function updateEducationAction(\Beech\Party\Domain\Model\Education $education) {
		$education->setParty($this->persistenceManager->getIdentifierByObject($education->getParty()));
		$this->educationRepository->update($education);
		$this->addFlashMessage('Updated.');
		$this->redirect('edit', NULL, NULL, array('person' => $education->getParty()));
	}

	/**
	 * @param \Beech\Party\Domain\Model\Education $education A new education to remove
	 *
	 * @return void
	 */
	public function removeEducationAction(\Beech\Party\Domain\Model\Education $education) {
		$person = $education->getParty();
		$education->setParty(NULL);
		$this->educationRepository->update($education);
		$this->addFlashMessage('Removed.');
		$this->redirect('edit', NULL, NULL, array('person' => $person));
	}

}

?>