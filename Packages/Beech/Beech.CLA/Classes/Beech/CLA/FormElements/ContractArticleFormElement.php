<?php
namespace Beech\CLA\FormElements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-02-13 09:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Contract article form element
 */
class ContractArticleFormElement extends \TYPO3\Form\Core\Model\AbstractFormElement {

	/**
	 * @var \Beech\Ehrm\Form\Helper\FieldDefaultValueHelper
	 * @Flow\Inject
	 */
	protected $fieldDefaultValueHelper;

	/**
	 * @var \Beech\Ehrm\Form\Helper\FieldValueLabelHelper
	 * @Flow\Inject
	 */
	protected $fieldValueLabelHelper;

	/**
	 * @var array
	 */
	public $articleValues;

	/**
	 * @var \Beech\CLA\Domain\Model\ContractArticle
	 */
	protected $contractArticle;

	/**
	 * Set contractArticle
	 *
	 * @param \Beech\CLA\Domain\Model\ContractArticle $contractArticle
	 */
	public function setContractArticle($contractArticle) {
		$this->setProperty('contractArticle', $contractArticle);
		$this->contractArticle = $contractArticle;
	}

	/**
	 * Get contractArticle
	 *
	 * @return \Beech\CLA\Domain\Model\ContractArticle
	 */
	public function getContractArticle() {
		return $this->contractArticle;
	}

	/**
	 * @param \TYPO3\Form\Core\Runtime\FormState $formState
	 */
	public function generateArticleText(\TYPO3\Form\Core\Runtime\FormState $formState) {

		$pattern = array();
		$replacement = array();
		$text = '';

			// replace values in text
		if ($this->contractArticle && !is_null($this->contractArticle->getValues())) {
			foreach ($this->contractArticle->getValues() as $contractValue) {
				if (isset($contractValue['valueId'])) {

					$identifier = $this->fieldDefaultValueHelper->generateIdentifierForArticle('contractCreator', $this->contractArticle->getArticleId(), $contractValue['valueId'] . '_text');
					$valueIdentifier = $this->fieldDefaultValueHelper->generateIdentifierForArticle('', $this->contractArticle->getArticleId(), $contractValue['valueId']);

					$pattern[] = sprintf('/<(%s)>/', $contractValue['valueId']);
					if (isset($this->articleValues[$contractValue['valueId']])) {
						$replacement[] = sprintf('<strong>%s</strong>', $this->fieldValueLabelHelper->getLabel($this->articleValues[$contractValue['valueId']], $contractValue));
					} else {
						$value = $formState->getFormValue($valueIdentifier);
						if (is_null($value)) {
							$value = $this->fieldDefaultValueHelper->getDefault($contractValue);
						}
						$replacement[] = sprintf('<strong id="%s">%s</strong>', $identifier, $this->fieldValueLabelHelper->getLabel($value, $contractValue));
					}
				}
			}
			$text = preg_replace($pattern, $replacement, $this->contractArticle->getArticleText());

			// only article text
		} elseif ($this->contractArticle) {
			$text = $this->contractArticle->getArticleText();
		}
		return $text;
	}
}

?>