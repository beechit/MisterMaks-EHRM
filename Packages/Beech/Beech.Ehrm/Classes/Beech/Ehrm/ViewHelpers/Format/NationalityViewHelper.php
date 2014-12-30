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