<?php
namespace Beech\Ehrm\Form\Helper;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 21-06-2013 15:18
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class FieldValueLabelHelper {

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
			if (!is_array($value)) {
				$value = array($value);
			}
			$return = array();
			foreach ($value as $id) {

				if (array_key_exists($id, $fieldInfo['options'])) {
					$return[] = $fieldInfo['options'][$id];
				} else {
					$return[] = $id;
				}
			}
			return implode(', ', $return);
		}

			// @todo: can be removed/changed when dates are saved correctly
		if (is_array($value) && array_key_exists('date', $value)) {
			$value = new \DateTime($value['date'], new \DateTimeZone($value['timezone']));
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