<?php
namespace Beech\Ehrm\Form\Helper;

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
 * @Flow\Scope("singleton")
 */
class FieldValueLabelHelper {

	/**
	 * @var \Beech\Ehrm\Utility\PreferencesUtility
	 * @Flow\Inject
	 */
	protected $preferencesUtility;

	/**
	 * Language selected for translations
	 * @var string
	 */
	protected $language = 'nl';

	/**
	 * Get label that belongs to a field value
	 *
	 * @todo implement localisation handling and maybe move this to formElement object(viewhelper)
	 * @param mixed $value
	 * @param array $fieldInfo
	 * @return string
	 */
	public function getLabel($value, $fieldInfo) {
		if (!empty($fieldInfo['options'])) {
			$locale = $this->preferencesUtility->getUserPreference('locale');
			$this->language = !empty($locale) ? substr($locale, 0, 2) : $this->language;
			if (!is_array($value)) {
				$value = array($value);
			}
			$return = array();
			foreach ($value as $id) {

				if (array_key_exists($id, $fieldInfo['options'])) {
					$return[] = isset($fieldInfo['translated'][$this->language]) ? $fieldInfo['translated'][$this->language][$id] : $fieldInfo['options'][$id];
				} else {
					$return[] = $id;
				}
			}
			return implode(', ', $return);
		}

			// convert CouchDB datetime value to PHP DateTime value
		if ($fieldInfo['type'] == 'Beech.Ehrm:DatePicker' && is_string($value) && !empty($value)) {
			$value = \DateTime::createFromFormat('Y-m-d H:i:s.u', $value);
		}

		if (is_object($value) && $value instanceof \DateTime) {
			$format = isset($fieldInfo['properties']['dateFormat']) ? $fieldInfo['properties']['dateFormat'] : \DateTime::W3C;
			$value = $value->format($format);
		} elseif (is_object($value)) {
			$value = 'object ' . get_class($value);
		} elseif (is_array($value)) {
			$value = 'array ' . print_r($value, 1);
		}
		return $value;
	}
}