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