<?php
namespace Beech\CLA\Finishers;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 05-06-12 10:47
 * All code (c) Beech Applications B.V. all rights reserved
 */

/**
 * This finisher extends default RedirectFinisher
 */
class RedirectToTemplateFinisher extends \TYPO3\Form\Finishers\RedirectFinisher {

	/**
	 * Executes this finisher
	 *
	 * @return void
	 * @throws \TYPO3\Form\Exception\FinisherException
	 */
	public function executeInternal() {
		$formValues = $this->finisherContext->getFormValues();
		$this->setOption('arguments', array_merge($formValues, $this->options));
		parent::executeInternal();
	}

}
?>