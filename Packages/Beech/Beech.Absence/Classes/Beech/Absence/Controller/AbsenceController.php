<?php
namespace Beech\Absence\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\Absence\Domain\Model\Absence as Absence;

/**
 * Absence controller for the Beech.Absence package
 *
 * @Flow\Scope("singleton")
 */
class AbsenceController extends \Beech\Ehrm\Controller\AbstractManagementController {

	/**
	 * @var string
	 */
	protected $entityClassName = 'Beech\Absence\Domain\Model\Absence';

	/**
	 * @var string
	 */
	protected $repositoryClassName = 'Beech\Absence\Domain\Repository\AbsenceRepository';

	/**
	 * @var \Beech\Party\Domain\Repository\PersonRepository
	 * @Flow\Inject
	 */
	protected $personRepository;

	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject
	 */
	protected $translator;

	/**
	 * Shows a form for creating a person sick report
	 *
	 * @param \Beech\Party\Domain\Model\Person $person
	 * @return void
	 */
	public function newAction(\Beech\Party\Domain\Model\Person $person = NULL) {
		$this->view->assign('person', $person);
		$this->view->assign('persons', $this->personRepository->findAll());
		$this->view->assign('absenceTypes', array('Sickness', 'Leave'));
	}

	/**
	 * @param \Beech\Absence\Domain\Model\Absence $absence
	 * @return void
	 */
	public function createAction(\Beech\Absence\Domain\Model\Absence $absence) {
		$this->repository->add($absence);
		$options = array('absence' => $absence);
		$this->emberRedirect('#/person');
	}

}
?>