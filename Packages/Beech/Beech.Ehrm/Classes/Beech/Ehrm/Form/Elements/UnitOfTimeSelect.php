<?php
namespace Beech\Ehrm\Form\Elements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 15-02-2013 13:03
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Unit of time select form element
 */
class UnitOfTimeSelect extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * Default language for translations
	 * @var string
	 */
	protected $language = 'en';

	/**
	 * Initialize form element
	 */
	public function initializeFormElement() {
		$this->setLabel('Unit of time');
		$unitsOfTime = array();
		$unitsOfTime['D'] = 'day';
		$unitsOfTime['W'] = 'week';
		$unitsOfTime['M'] = 'month';

		$locale = $this->preferencesUtility->getUserPreference('locale');
		$this->setProperty('language', !empty($locale) ? substr($locale, 0, 2) : $this->language);
		$this->setProperty('options', $unitsOfTime);
	}

}
