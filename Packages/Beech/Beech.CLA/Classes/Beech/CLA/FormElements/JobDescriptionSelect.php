<?php
namespace Beech\CLA\FormElements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-02-13 09:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Job description select form element
 */
class JobDescriptionSelect extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * @var \Beech\CLA\Domain\Repository\JobDescriptionRepository
	 * @Flow\Inject
	 */
	protected $jobDescriptionRepository;

	/**
	 * Initialize form element
	 */
	public function initializeFormElement() {
		$this->setLabel('Job Description');
		$jobDescriptions = $this->jobDescriptionRepository->findAll();
		$jobDescriptionsArray = array();
		foreach ($jobDescriptions as $jobDescription) {
			$jobDescriptionsArray[$jobDescription->getId()] = $jobDescription->getJobTitle();
		}
		$this->setProperty('options', $jobDescriptionsArray);
	}
}
?>