<?php
namespace Beech\Party\Form\Elements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15-02-2013 13:03
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Work location select form element
 */
class WorkDaySelect extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * Initialize form element
	 */
	public function initializeFormElement() {
		$this->setLabel('Work days');

		$locale = $this->preferencesUtility->getUserPreference('locale');
		$this->setProperty('language', !empty($locale) ? substr($locale, 0, 2) : $this->language);
	}

}
