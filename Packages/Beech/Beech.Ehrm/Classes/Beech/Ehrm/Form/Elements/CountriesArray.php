<?php
/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 29-05-2013 17:03
 * All code (c) Beech Applications B.V. all rights reserved
 */

namespace Beech\Ehrm\Form\Elements;

use TYPO3\Flow\Annotations as Flow;

/**
 * Class CountriesArray
 * It is singleton, to read data only once if necessary and reuse it all the time
 *
 * @Flow\Scope("singleton")
 */
class CountriesArray {

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
	 * Storage container for countries
	 * @var array
	 */
	public $countries = array();

	/**
	 * @var \Beech\Ehrm\Utility\PreferenceUtility
	 * @Flow\Inject
	 */
	protected $preferenceUtility;

	/**
	 * Get countries array. If array is empty read data from yaml file
	 *
	 * @return array
	 */
	public function getCountries() {
		$locale = $this->preferenceUtility->getUserPreference('locale');
		$this->language = !empty($locale) ? substr($locale, 0, 2) : $this->language;
		if (empty($this->countries) || ($locale != NULL && $this->language != $locale)) {
			$parsedYaml = \Symfony\Component\Yaml\Yaml::parse($this->dataFile);
			foreach ($parsedYaml['country']['values'] as $index => $country) {
				$this->countries[$country] = $parsedYaml['country']['translation'][$this->language][$index];
			}
		}
		return $this->countries;
	}

}