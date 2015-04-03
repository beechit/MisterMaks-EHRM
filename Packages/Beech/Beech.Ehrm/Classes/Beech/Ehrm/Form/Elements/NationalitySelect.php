<?php
namespace Beech\Ehrm\Form\Elements;

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

use TYPO3\Flow\Annotations as Flow;

/**
 * A selection field for country
 */
class NationalitySelect extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * Location of yaml file with countries
	 * @var string
	 */
	protected $dataFile = 'resource://Beech.Ehrm/Private/Generator/Yaml/nationality.yaml';

	/**
	 * Language selected for translations
	 * @var string
	 */
	protected $language = 'nl';

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * @return void
	 */
	public function initializeFormElement() {
			// check user's locale
		$locale = $this->preferencesUtility->getUserPreference('locale');
		$this->language = !empty($locale) ? substr($locale, 0, 2) : $this->language;
			//get data from yaml file
		$parsedYaml = \Symfony\Component\Yaml\Yaml::parse($this->dataFile);
			// prepare array
		$nationalitiesArray = array();
		foreach ($parsedYaml['nationality'] as $nationality) {
			$nationalitiesArray[$nationality['identifier']] = $nationality['translation'][$this->language];
		}
		$this->setLabel('Nationality');
		$this->setProperty('options', $nationalitiesArray);
	}

}

?>