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
 */
class CountrySelect extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * Location of yaml file with countries
	 * @var string
	 */
	protected $dataFile = 'resource://Beech.Ehrm/Private/Generator/Yaml/country.yaml';

	/**
	 * Language selected for translations
	 * @var string
	 */
	protected $language = 'nl';

	/**
	 * @return void
	 */
	public function initializeFormElement() {
		$parsedYaml = \Symfony\Component\Yaml\Yaml::parse($this->dataFile);
		foreach ($parsedYaml['country']['values'] as $index => $country) {
			$countriesArray[$country] = $parsedYaml['country']['translation'][$this->language][$index];
		}
		$this->setLabel('Country');
		$this->setProperty('options', $countriesArray);
	}

}

?>