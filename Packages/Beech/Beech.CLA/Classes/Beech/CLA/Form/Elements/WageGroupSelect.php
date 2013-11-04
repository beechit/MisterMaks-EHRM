<?php
namespace Beech\CLA\Form\Elements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-02-13 09:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Contract template form element
 */
class WageGroupSelect extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * @var \Beech\CLA\Domain\Repository\SalaryScaleRepository
	 * @Flow\Inject
	 */
	protected $salaryScaleRepository;

	/**
	 * Initialize form element
	 * @return void
	 */
	public function initializeFormElement() {
		$salaryScales = $this->salaryScaleRepository->findAll();
		$this->setLabel('Salary group');
		$options = array();
		foreach ($salaryScales as $salaryScale) {
			foreach ($salaryScale->getWageGroups() as $wageGroup) {
				$options[$wageGroup['label']] = $wageGroup['label'];
			}
		}
		$this->setProperty('options', $options);

	}

}

?>