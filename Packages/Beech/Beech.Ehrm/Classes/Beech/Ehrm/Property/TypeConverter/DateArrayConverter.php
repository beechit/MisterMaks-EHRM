<?php
namespace Beech\Ehrm\Property\TypeConverter;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 13-05-13 13:19
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
	protected $priority = 1;

	public function canConvertFrom($source, $targetType) {
		return TRUE;
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