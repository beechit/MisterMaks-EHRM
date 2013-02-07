<?php
namespace Beech\CLA\Administration\Controller;

/*                                                                        *
 * This source file is proprietary property of Beech Applications B.V.
 * All code (c) Beech Applications B.V. all rights reserved
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Beech\CLA\Domain\Model\JobDescription;

/**
* JobDescription controller for the Beech.CLA package  and subpackage Administration
*
* @Flow\Scope("singleton")
*/
class JobDescriptionController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\CLA\Domain\Model\JobDescription';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\CLA\Domain\Repository\JobDescriptionRepository';

	/**
	 * Adds the given new job description object to the job description repository
	 *
	 * @param \Beech\CLA\Domain\Model\JobDescription $jobDescription A new job description to add
	 * @return void
	 */
	public function createAction(JobDescription $jobDescription) {
		$this->repository->add($jobDescription);
		$this->documentManager->merge($jobDescription->getDocument());
		$this->addFlashMessage('Created a new job description');
		$this->redirect('list');
	}

	/**
	 * Shows a single job description object
	 *
	 * @param \Beech\CLA\Domain\Model\JobDescription $jobDescription The job description to show
	 * @return void
	 */
	public function showAction(\Beech\CLA\Domain\Model\JobDescription $jobDescription) {
		$this->view->assign('jobdescription', $jobDescription);
	}

	/**
	* Updates the given job description object
	*
	* @param \Beech\CLA\Domain\Model\JobDescription $jobDescription The job description to update
	* @return void
	*/
	public function updateAction(JobDescription $jobDescription) {
		$this->repository->update($jobDescription);
		$this->addFlashMessage('Updated the job description.');
		$this->redirect('list');
	}

	/**
	 * Shows a form for editing an existing job description object
	 *
	 * @param \Beech\CLA\Domain\Model\JobDescription $jobDescription The job description to edit
	 * @return void
	 */
	public function editAction(JobDescription $jobDescription) {
		$this->view->assign('jobDescription', $jobDescription);
	}

	/**
	* Removes the given job description object from the job description repository
	*
	* @param \Beech\CLA\Domain\Model\JobDescription $jobDescription The job description to delete
	* @return void
	*/
	public function deleteAction(JobDescription $jobDescription) {
		$this->repository->remove($jobDescription);
		$this->redirect('list');
	}

}

?>