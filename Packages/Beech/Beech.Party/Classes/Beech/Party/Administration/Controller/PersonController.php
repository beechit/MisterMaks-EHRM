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
	 * @param \Beech\Party\Domain\Model\Address $address A new address to add
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
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumber A  phoneNumber to add
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
}

?>