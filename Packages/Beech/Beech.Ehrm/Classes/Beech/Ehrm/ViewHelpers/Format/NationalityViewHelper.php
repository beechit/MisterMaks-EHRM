<?php
namespace Beech\Ehrm\ViewHelpers\Format;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 17-07-2013 16:16
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\I18n;
use TYPO3\Fluid\Core\ViewHelper;

/**
 * Formats a nationality.
 *
 * = Examples =
 *
 * <code title="Defaults">
 * <ehrm:format.nationality>{nationality}</f:format.nationality>
 * </code>
 * <output>
 * english
 * (depending on nationality code)
 * </output>
 *
 * <code title="Inline notation">
 * {nationality -> ehrm:format.nationality()}
 * </code>
 * <output>
 * german
 * (depending on the value of {nationality})
 * </output>
 */
class NationalityViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Location of yaml file with countries
	 * @var string
	 */
	protected $dataFile = 'resource://Beech.Ehrm/Private/Generator/Yaml/nationality.yaml';

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\I18n\Service
	 */
	protected $localizationService;

	/**
	 * Render the supplied nationality as a string , based on code value and user locale.
	 *
	 * @param mixed $nationality
	 *
	 * @throws \TYPO3\Fluid\Core\ViewHelper\Exception
	 * @return string Nationality in current locale
	 * @api
	 */
	public function render($nationality = NULL) {
		if ($nationality === NULL) {
			$nationality = $this->renderChildren();
			if ($nationality === NULL) {
				return '';
			}
		}
		$language = substr($this->localizationService->getConfiguration()->getCurrentLocale(), 0, 2);

		$parsedNationalities = \Symfony\Component\Yaml\Yaml::parse($this->dataFile);
		$output = $nationality;
		foreach ($parsedNationalities['nationality'] as $parsedNationality) {

			if ($parsedNationality['identifier'] === $nationality) {
				$output = $parsedNationality['translation'][$language];
			}
		}

		return $output;
	}

}

?>