<?php
namespace Beech\Ehrm\ViewHelpers\Format;

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
use TYPO3\Flow\I18n;
use TYPO3\Fluid\Core\ViewHelper;

/**
 * Formats a country.
 *
 * = Examples =
 *
 * <code title="Defaults">
 * <ehrm:format.country>de</f:format.country>
 * </code>
 * <output>
 * Germany
 * (depending on user language and country code)
 * </output>
 *
 * <code title="Inline notation">
 * {country -> ehrm:format.country()}
 * </code>
 * <output>
 * South Africa
 * (depending on the value of {country})
 * </output>
 */
class CountryViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * Render the supplied country as a full name of country , based on code value and user locale.
	 *
	 * Notice: User locale is read inside Beech\Ehrm\Form\Elements\CountriesArray class
	 *
	 * @param mixed $country
	 *
	 * @throws \TYPO3\Fluid\Core\ViewHelper\Exception
	 * @return string Full name of country
	 * @api
	 */
	public function render($country = NULL) {
		if ($country === NULL) {
			$country = $this->renderChildren();
			if ($country === NULL) {
				return '';
			}
		}
		$output = $country;
		if ($this->objectManager->isRegistered('Beech\Ehrm\Form\Elements\CountriesArray')) {
			$countriesArray = $this->objectManager->get('Beech\Ehrm\Form\Elements\CountriesArray')->getCountries();
			if (isset($countriesArray[$country])) {
				$output = $countriesArray[$country];
			}
		} else {
			throw new \Exception('Array of countries cannot be loaded');
		}

		return $output;
	}

}

?>