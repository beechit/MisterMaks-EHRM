<?php
namespace Beech\Party\Controller\Management;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\FLOW3\Annotations as FLOW3;
use \Beech\Party\Domain\Model\Person;

/**
 * Person controller for the Beech.Party package
 *
 * @FLOW3\Scope("singleton")
 */
class PersonController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @FLOW3\Inject
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 */
	protected $personRepository;

	/**
	 * Shows a list of people
	 *
	 * @return void
	 */
	public function listAction() {
		$this->view->assign('people', $this->personRepository->findAll());
	}

	/**
	 * Shows a single person object
	 *
	 * @param \Beech\Party\Domain\Model\Person $person The person to show
	 * @return void
	 */
	public function showAction(Person $person) {
		$this->view->assign('person', $person);
	}

	/**
	 * Shows a form for creating a new person object
	 *
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * Adds the given new person object to the person repository
	 *
	 * @param \Beech\Party\Domain\Model\Person $newPerson A new person to add
	 * @param string $email
	 * @param string $phone
	 * @return void
	 */
	public function createAction(Person $newPerson, $email, $phone) {
		$newPerson->addEmail($email);
		$newPerson->addPhone($phone);
		$this->personRepository->add($newPerson);
		$this->addFlashMessage('Created a new person.');
		$this->redirect('list');
	}

	/**
	 * Shows a form for editing an existing person object
	 *
	 * @param \Beech\Party\Domain\Model\Person $person The person to edit
	 * @return void
	 */
	public function editAction(Person $person) {
		$this->view->assign('person', $person);
	}

	/**
	 * Updates the given person object
	 *
	 * @param \Beech\Party\Domain\Model\Person $person The person to update
	 * @return void
	 */
	public function updateAction(Person $person) {
		$this->personRepository->update($person);
		$this->addFlashMessage('Updated the person.');
		$this->redirect('list');
	}

	/**
	 * Removes the given person object from the person repository
	 *
	 * @param \Beech\Party\Domain\Model\Person $person The person to delete
	 * @return void
	 */
	public function deleteAction(Person $person) {
		$this->personRepository->remove($person);
		$this->addFlashMessage('Deleted a person.');
		$this->redirect('list');
	}

	/**
	 * Redirect to list action
	 *
	 * @return void
	 */
	public function redirectAction() {
		$this->redirect('list');
	}

}

?>