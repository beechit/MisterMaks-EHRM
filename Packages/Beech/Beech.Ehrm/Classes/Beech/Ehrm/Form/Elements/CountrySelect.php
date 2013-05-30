<?php
namespace Beech\Ehrm\Form\Elements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 20/02/13 14:04 AM
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * A selection field for country
 *
 * @Flow\Scope("singleton")
 */
class CountrySelect extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @return void
	 */
	public function initializeFormElement() {
		if ($this->objectManager->isRegistered('Beech\Ehrm\Form\Elements\CountriesArray')) {
			$countriesArray = $this->objectManager->get('Beech\Ehrm\Form\Elements\CountriesArray')->getCountries();
		} else {
			throw new \Exception('Array of countries cannot be loaded');
		}
		$this->setLabel('Country');
		$this->setProperty('options', $countriesArray);
	}

}

?>