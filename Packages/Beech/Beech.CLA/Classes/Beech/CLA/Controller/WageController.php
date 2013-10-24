<?php
namespace Beech\CLA\Controller;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-05-13 09:49
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use Beech\CLA\Domain\Model\Wage as Wage;

/**
 * Wage controller for the Beech.CLA package
 *
 * @Flow\Scope("singleton")
 */
class WageController extends \Beech\Ehrm\Controller\AbstractController {

	/**
	 * @var \Beech\CLA\Domain\Repository\SalaryScaleRepository
	 * @Flow\Inject
	 */
	protected $salaryScaleRepository;

	/**
	 * @param $salaryScale
	 * @param string $wageScaleGroup
	 */
	public function filterAction(\Beech\CLA\Domain\Model\SalaryScale $salaryScale, $wageScaleGroup) {
		$salaryScale = $this->salaryScaleRepository->findByIdentifier($this->request->getArgument('salaryScale'));
		$options = array(array('label' => '', 'value' => ''));
		foreach ($salaryScale->getWageGroups() as $wageGroup) {
			if ($wageGroup['label'] === $this->request->getArgument('wageScaleGroup')) {
				foreach ($wageGroup['data'] as $wageStep) {
					$options[] = array('label' => $wageStep['step'], 'value' => $wageStep['step'], 'wage' => $wageStep['value']);
				}
			}
		}
		$this->view->assign('options', $options);
	}
}

?>