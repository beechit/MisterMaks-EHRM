<?php
namespace Beech\CLA\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\CLA\Domain\Model\JobPosition as JobPosition;

/**
 * JobPosition controller for the Beech.Ehrm package
 * @Flow\Scope("singleton")
 */
class JobPositionController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\CLA\Domain\Repository\JobPositionRepository
	 * @Flow\Inject
	 */
	protected $jobPositionRepository;

	/**
	 * @var \Beech\CLA\Domain\Repository\JobDescriptionRepository
	 * @Flow\Inject
	 */
	protected $jobDescriptionRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * @var \Beech\Party\Domain\Repository\CompanyRepository
	 * @Flow\Inject
	 */
	protected $companyRepository;

	/**
	 * Index action (visial overview)
	 *
	 * @param string $random
	 */
	public function indexAction($random = NULL) {
		$this->view->assign('jobPosition', $this->jobPositionRepository->findOneByParentId(''));
	}

	/**
	 * @param \Beech\CLA\Domain\Model\JobPosition $parentJobPosition
	 * @param \Beech\CLA\Domain\Model\JobPosition $jobPosition
	 * @return void
	 */
	public function newAction(JobPosition $parentJobPosition, JobPosition $jobPosition = NULL) {
		if ($jobPosition === NULL) {
			$jobPosition = new \Beech\CLA\Domain\Model\JobPosition();
		}
		$jobPosition->setParent($parentJobPosition);
		$jobPosition->setDepartment($parentJobPosition->getDepartment());

		$this->view->assign('persons', $this->personRepository->findAll());
		$this->view->assign('jobPositions', $this->jobPositionRepository->findAll());
		$this->view->assign('jobDescriptions', $this->jobDescriptionRepository->findAll());
		$this->view->assign('departments', $this->companyRepository->findAll());
		$this->view->assign('jobPosition', $jobPosition);
	}

	/**
	 * Adds the given new task object to the task repository
	 *
	 * @param \Beech\CLA\Domain\Model\JobPosition $jobPosition A new task to add
	 * @return void
	 */
	public function createAction(JobPosition $jobPosition) {
		$this->jobPositionRepository->add($jobPosition);
		$this->addFlashMessage('Created a new job position');
		$this->emberRedirect('#/jobpositions/' . time());
	}

	/**
	 * Shows a single job position object
	 *
	 * @param \Beech\CLA\Domain\Model\JobPosition $jobPosition The job position to show
	 * @Flow\IgnoreValidation("$jobPosition")
	 * @return void
	 */
	public function showAction(JobPosition $jobPosition) {
		$this->view->assign('jobPosition', $jobPosition);
	}

	/**
	 * Shows a form for editing an existing job position object
	 *
	 * @param \Beech\CLA\Domain\Model\JobPosition $jobPosition The job position to edit
	 * @Flow\IgnoreValidation("$jobPosition")
	 * @return void
	 */
	public function editAction(JobPosition $jobPosition) {

		$this->view->assign('persons', $this->personRepository->findAll());
		$this->view->assign('jobPositions', $this->jobPositionRepository->findAllowedParentsFor($jobPosition));
		$this->view->assign('jobDescriptions', $this->jobDescriptionRepository->findAll());
		$this->view->assign('departments', $this->companyRepository->findAll());
		$this->view->assign('jobPosition', $jobPosition);
	}

	/**
	 * Updates the given job position object
	 *
	 * @param \Beech\CLA\Domain\Model\JobPosition $jobPosition The job position to update
	 * @return void
	 */
	public function updateAction(JobPosition $jobPosition) {
		$this->jobPositionRepository->update($jobPosition);
		$this->addFlashMessage('Updated the job position');
		$this->emberRedirect('#/jobpositions/' . time());
	}
}

?>