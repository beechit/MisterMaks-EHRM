<?php
namespace Beech\Ehrm\Property\TypeConverter;

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
 * Converter date array's to datetimestring ready for DB
 * Todo: this needs to get obsolete, all dates should get type: DateTime in YAML
 *
 * @api
 * @Flow\Scope("singleton")
 */
class DateArrayConverter extends \TYPO3\Flow\Property\TypeConverter\DateTimeConverter {

	/**
	 * @var array<string>
	 */
	protected $sourceTypes = array('array');

	/**
	 * @var string
	 */
	protected $targetType = 'string';

	/**
	 * @var integer
	 */
	protected $priority = 2;

	/**
	 * Check if source matches expected input
	 *
	 * @param mixed $source
	 * @param string $targetType
	 * @return bool
	 */
	public function canConvertFrom($source, $targetType) {
		if(is_array($source) && array_key_exists('date', $source)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Convert from $source to $targetType
	 *
	 * @param array $source
	 * @param string $targetType
	 * @param array $convertedChildProperties
	 * @param \TYPO3\Flow\Property\PropertyMappingConfigurationInterface $configuration
	 * @return array
	 * @api
	 */
	public function convertFrom($source, $targetType, array $convertedChildProperties = array(), \TYPO3\Flow\Property\PropertyMappingConfigurationInterface $configuration = NULL) {
		$dateFormatted = parent::convertFrom($source, 'DateTime', $convertedChildProperties, $configuration);
		return ($dateFormatted) ? $dateFormatted->format('Y-m-d H:i:s.u') : NULL;
	}
}
?>