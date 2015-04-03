<?php
namespace Beech\Ehrm\ViewHelpers\Form;

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
 * Display a jQuery date picker.
 *
 * Note: Requires bootstrap.datepicker plugin
 */
class DatePickerViewHelper extends \TYPO3\Fluid\ViewHelpers\Form\AbstractFormFieldViewHelper {

	/**
	 * @var string
	 */
	protected $tagName = 'input';

	/**
	 * @var \TYPO3\Flow\Property\PropertyMapper
	 * @Flow\Inject
	 */
	protected $propertyMapper;

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
	 * Initialize the arguments.
	 *
	 * @return void
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerTagAttribute('size', 'int', 'The size of the input field');
		$this->registerTagAttribute('placeholder', 'string', 'Specifies a short hint that describes the expected value of an input element');
		$this->registerArgument('errorClass', 'string', 'CSS class to set if there are errors for this view helper', FALSE, 'f3-form-error');
		$this->registerArgument('initialDate', 'string', 'Initial date (@see http://www.php.net/manual/en/datetime.formats.php for supported formats)');
		$this->registerUniversalTagAttributes();
	}

	/**
	 * Renders the text field, hidden field and required javascript
	 *
	 * @param string $dateFormat
	 * @param boolean $enableDatePicker
	 * @param boolean $displayDateSelector
	 * @param boolean $enableTimePicker
	 * @return string
	 */
	public function render($dateFormat = NULL, $enableDatePicker = TRUE, $displayDateSelector = TRUE, $enableTimePicker = FALSE) {
		$name = $this->getName();
		$this->registerFieldNameForFormTokenGeneration($name);

			// get current locale & language
		$locale = $this->preferencesUtility->getUserPreference('locale');
		$this->language = !empty($locale) ? substr($locale, 0, 2) : $this->language;

		if ($dateFormat === NULL) {
			$dateFormat = $this->getCurrentDateFormat($this->language);
		}
		if ($enableTimePicker) {
			$dateFormat .= ' H:i';
		}

		if ($displayDateSelector) {
			$this->tag->addAttribute('type', 'text');
		} else {
			$this->tag->addAttribute('type', 'hidden');
		}
		$this->tag->addAttribute('name', $name . '[date]');
		if ($enableDatePicker) {
			$this->tag->addAttribute('readonly', TRUE);
		}
		$date = $this->getSelectedDate();
		if ($date !== NULL) {
			$this->tag->addAttribute('value', $date->format($dateFormat));
		}

		if ($this->hasArgument('id')) {
			$id = $this->arguments['id'];
		} else {
			$id = 'field' . md5(uniqid());
			$this->tag->addAttribute('id', $id);
		}

		$this->tag->addAttribute('data-date-language', $this->language);

		$this->setErrorClassAttribute();
		$content = '';
		$content .= '<div class="input-append">';
		if ($displayDateSelector === TRUE && $enableTimePicker === TRUE) {
			$datePickerDateFormat = $this->convertDateFormatToDatePickerFormat($dateFormat);
			$this->tag->addAttribute('class', $this->tag->getAttribute('class') . ' withTime');
			$this->tag->addAttribute('data-date-format', $datePickerDateFormat);
		}
		$content .= $this->tag->render();
		if ($displayDateSelector === TRUE) {
			$content .= '<span class="datepickerIcon add-on"><i class="icon-calendar"></i></span>';
		}
		$content .= '</div>';
		$content .= '<input type="hidden" name="' . $name . '[dateFormat]" value="' . htmlspecialchars($dateFormat) . '" />';

		return $content;
	}

	/**
	 * @return \DateTime
	 */
	protected function getSelectedDate() {
		$date = $this->getValue();
		if ($date instanceof \DateTime) {
			return $date;
		}

		if ($date !== NULL) {
			if (is_string($date)) {
				$date = array('date' => $date, 'dateFormat' => 'Y-m-d H:i:s.u');
			}
			$date = $this->propertyMapper->convert($date, 'DateTime');

			if (!$date instanceof \DateTime) {
				return NULL;
			}
			return $date;
		}
		if ($this->hasArgument('initialDate')) {
			return new \DateTime($this->arguments['initialDate']);
		}
	}

	/**
	 * @param string $dateFormat
	 * @return string
	 */
	protected function convertDateFormatToDatePickerFormat($dateFormat) {
		$replacements = array(
			'd' => 'dd',
			'D' => 'dd',
			'm' => 'mm',
			'M' => 'm',
			'Y' => 'yyyy',
			'y' => 'yy',
			'H' => 'hh',
			'i' => 'ii'
		);
		return strtr($dateFormat, $replacements);
	}

	/**
	 * TODO: Move this to settings.yaml
	 */
	protected function getCurrentDateFormat($language) {
		if ($language === 'nl') {
			$dateFormat = 'd-m-Y';
		} else {
			$dateFormat = 'Y-m-d';
		}
		return $dateFormat;
	}
}

?>