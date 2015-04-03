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
