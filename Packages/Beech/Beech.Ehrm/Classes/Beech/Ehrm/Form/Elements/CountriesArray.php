<?php
/*                                                                        *
 * This script belongs to beechit/mrmaks.                                 *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

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
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * Get countries array. If array is empty read data from yaml file
	 *
	 * @return array
	 */
	public function getCountries() {
		$locale = $this->preferencesUtility->getUserPreference('locale');
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