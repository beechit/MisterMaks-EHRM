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
class SalaryScaleSelect extends \TYPO3\Form\Core\Model\AbstractFormElement {

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
		$this->setLabel('Salary scale');
		$this->setProperty('options', $salaryScales);
		$this->setProperty('optionLabelField', 'wageName');
	}

}

?>