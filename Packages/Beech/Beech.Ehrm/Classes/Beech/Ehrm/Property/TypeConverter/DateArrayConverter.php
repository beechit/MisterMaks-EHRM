<?php
namespace Beech\Ehrm\Property\TypeConverter;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 13-05-13 13:19
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Converter which transforms arrays to arrays.
 *
 * @api
 * @Flow\Scope("singleton")
 */
class DateArrayConverter extends \TYPO3\Flow\Property\TypeConverter\AbstractTypeConverter {

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

	/**
	 * Actually convert from $source to $targetType, in fact a noop here.
	 *
	 * @param array $source
	 * @param string $targetType
	 * @param array $convertedChildProperties
	 * @param \TYPO3\Flow\Property\PropertyMappingConfigurationInterface $configuration
	 * @return array
	 * @api
	 */
	public function convertFrom($source, $targetType, array $convertedChildProperties = array(), \TYPO3\Flow\Property\PropertyMappingConfigurationInterface $configuration = NULL) {
		$dateFormatted = \DateTime::createFromFormat($source['dateFormat'], $source['date']);
		return ($dateFormatted) ? $dateFormatted->format('Y-m-d H:i:s') : NULL;
	}
}
?>