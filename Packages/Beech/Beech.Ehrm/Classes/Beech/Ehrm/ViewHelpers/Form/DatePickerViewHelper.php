<?php
namespace Beech\Ehrm\ViewHelpers\Form;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 13-02-13 13:19
 * All code (c) Beech Applications B.V. all rights reserved
 */

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
	 * @var TYPO3\Flow\Property\PropertyMapper
	 * @Flow\Inject
	 */
	protected $propertyMapper;

	/**
	 * Initialize the arguments.
	 *
	 * @return void

	 * @api
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
	 * @return string
	 */
	public function render($dateFormat = 'Y-m-d', $enableDatePicker = TRUE, $displayDateSelector = TRUE) {
		$name = $this->getName();
		$this->registerFieldNameForFormTokenGeneration($name);

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
		$this->setErrorClassAttribute();
		$content = '';
		$content .= '<div class="input-append">';
		$content .= $this->tag->render();
		if ($displayDateSelector) {
			$content .= '<span class="add-on"><i class="icon-calendar"></i></span>';
		}
		$content .= '</div>';
		$content .= '<input type="hidden" name="' . $name . '[dateFormat]" value="' . htmlspecialchars($dateFormat) . '" />';

		if ($displayDateSelector && $enableDatePicker) {
			$datePickerDateFormat = $this->convertDateFormatToDatePickerFormat($dateFormat);
			$content .= '<script type="text/javascript">//<![CDATA[
				$(function() {
					$("#' . $id . '").datepicker({
						format: "' . $datePickerDateFormat . '"
					});
				});
				//]]></script>';
		}
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
			'y' => 'yy'
		);
		return strtr($dateFormat, $replacements);
	}

}

?>