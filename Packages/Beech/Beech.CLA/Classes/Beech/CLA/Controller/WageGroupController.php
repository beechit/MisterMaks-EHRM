<?php
namespace Beech\CLA\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\CLA\Domain\Model\WageGroup as WageGroup;

/**
 * WageGroup controller for the Beech.CLA package
 *
 * @Flow\Scope("singleton")
 */
class WageGroupController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\CLA\Domain\Repository\SalaryScaleRepository
	 * @Flow\Inject
	 */
	protected $salaryScaleRepository;

	/**
	 */
	public function filterAction() {
		$salaryScale = $this->salaryScaleRepository->findByIdentifier($this->request->getArgument('salaryScale'));
		$options = array('' => '');
		foreach ($salaryScale->getWageGroups() as $wageGroup) {
			$options[$wageGroup['label']] = $wageGroup['label'];
		}
		$this->view->assign('options', $options);
	}
}
?>