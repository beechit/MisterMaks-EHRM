<?php
namespace Beech\Party\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Party\Domain\Model\PersonRelation as PersonRelation;
use Beech\Party\Domain\Model\PhoneNumber as PhoneNumber;
use Beech\Party\Domain\Model\ElectronicAddress as ElectronicAddress;

/**
 * Education controller for the Beech.Party package
 * @Flow\Scope("singleton")
 */
class PersonRelationController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Party\Domain\Model\PersonRelation';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Party\Domain\Repository\PersonRelationRepository';

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

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
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * @param \Beech\Party\Domain\Model\Person $person
	 */
	public function listAction(\Beech\Party\Domain\Model\Person $person) {
		$this->view->assign('person', $person);
		$this->view->assign('persons', $this->personRepository->findAll());
		$this->view->assign('personRelations', $this->repository->findAll());
	}

	/**
	 * Shows a form for creating a new person object with relation
	 *
	 * @param \Beech\Party\Domain\Model\Person $personRelatedTo
	 * @return void
	 */
	public function newAction(\Beech\Party\Domain\Model\Person $personRelatedTo) {
		$this->view->assign('personRelatedTo', $personRelatedTo);
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to add
	 * @param \Beech\Party\Domain\Model\Person $personRelatedTo
	 * @return void
	 */
	public function createAction(\Beech\Party\Domain\Model\Person $person, \Beech\Party\Domain\Model\Person $personRelatedTo) {
		$relationType = $person->getRelationType();
		$emergencyContact = $person->getEmergencyContact();
		$phoneNumberValue = $person->getPhoneNumber();
		$electronicAddressValue = $person->getElectronicAddress();
		$this->personRepository->add($person);
		$this->addFlashMessage('Person is added');

		$personRelation = new \Beech\Party\Domain\Model\PersonRelation();
		$personRelation->setPerson($person);
		$personRelation->setPersonRelatedTo($personRelatedTo);
		$personRelation->setRelationType($relationType);
		$personRelation->setEmergencyContact($emergencyContact ? $emergencyContact : FALSE);
		$this->repository->add($personRelation);

		$phoneNumber = new PhoneNumber();
		$phoneNumber->setParty($this->persistenceManager->getIdentifierByObject($person));
		$phoneNumber->setPhoneNumberType(PhoneNumber::TYPE_HOME);
		$phoneNumber->setPrimary(TRUE);
		$phoneNumber->setPhoneNumber($phoneNumberValue);
		$this->phoneNumberRepository->add($phoneNumber);

		$electronicAddress = new ElectronicAddress();
		$electronicAddress->setParty($this->persistenceManager->getIdentifierByObject($person));
		$electronicAddress->setElectronicAddressType(ElectronicAddress::TYPE_EMAIL);
		$electronicAddress->setAddress($electronicAddressValue);
		$this->electronicAddressRepository->add($electronicAddress);

		$options = array('person' => $person, 'personRelation' => $personRelation);
		$this->redirect('edit', NULL, NULL, $options);
	}

	/**
	 * Edit person object with relation
	 *
	 * @param \Beech\Party\Domain\Model\Person $personRelatedTo
	 * @return void
	 */
	public function editAction(\Beech\Party\Domain\Model\Person $person = NULL, \Beech\Party\Domain\Model\PersonRelation $personRelation) {
		$this->view->assign('person', $person);
		$this->view->assign('personRelation', $personRelation);
		$phoneNumbers = $this->phoneNumberRepository->findByParty($person);
		$electronicAddresses = $this->electronicAddressRepository->findByParty($person);
		if (isset($phoneNumbers[0])) {
			$this->view->assign('phoneNumber', $phoneNumbers[0]);
		}
		if (isset($electronicAddresses[0])) {
			$this->view->assign('electronicAddress', $electronicAddresses[0]);
		}
	}

	/**
	 * @param \Beech\Party\Domain\Model\Person $person A new person to edit
	 * @param \Beech\Party\Domain\Model\PersonRelation $personRelation
	 * @param \Beech\Party\Domain\Model\PhoneNumber $phoneNumberObject
	 * @param \Beech\Party\Domain\Model\ElectronicAddress $electronicAddressObject
	 * @Flow\IgnoreValidation("phoneNumberObject")
	 * @Flow\IgnoreValidation("electronicAddressObject")
	 * @return void
	 */
	public function updateAction(\Beech\Party\Domain\Model\Person $person, \Beech\Party\Domain\Model\PersonRelation $personRelation, PhoneNumber $phoneNumberObject, ElectronicAddress $electronicAddressObject) {
		$this->personRepository->update($person);
		$phoneNumberObject->setPhoneNumber($person->getPhoneNumber());
		$this->phoneNumberRepository->update($phoneNumberObject);
		$electronicAddressObject->setAddress($person->getElectronicAddress());
		$this->electronicAddressRepository->update($electronicAddressObject);
		$options = array('person' => $person, 'personRelation' => $personRelation);
		$this->redirect('edit', NULL, NULL, $options);
	}

	/**
	 * @param \Beech\Party\Domain\Model\PersonRelation $personRelation
	 * @return void
	 */
	public function removeAction(\Beech\Party\Domain\Model\PersonRelation $personRelation) {
		$this->personRepository->remove($personRelation->getPerson());
		$this->repository->remove($personRelation);
		$this->addFlashMessage($this->translator->translateById('Removed.', array(), NULL, NULL, 'Actions', 'Beech.Ehrm'));
	}

}

?>