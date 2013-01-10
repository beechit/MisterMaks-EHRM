<?php
namespace Beech\Party\Administration\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 23-08-12 14:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use \Beech\Party\Domain\Model\Person;

/**
* Person controller for the Beech.Party package  and subpackage Administration
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
	* Adds the given new person object to the person repository
	*
	* @param \Beech\Party\Domain\Model\Person $person A new person to add
	* @return void
	*/
	public function createAction(Person $person) {
		$this->repository->add($person);
		$this->documentManager->merge($person->getDocument());
		$this->addFlashMessage('Created a new person');
		$this->redirect('list');
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
	* Updates the given person object
	*
	* @param \Beech\Party\Domain\Model\Person $person The person to update
	* @return void
	*/
	public function updateAction(Person $person) {
		$this->repository->update($person);
		$this->addFlashMessage('Updated the person.');
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
	* Removes the given person object from the person repository
	*
	* @param \Beech\Party\Domain\Model\Person $person The person to delete
	* @return void
	*/
	public function deleteAction(Person $person) {
		$this->repository->remove($person);
		$this->redirect('list');
	}

}

?>