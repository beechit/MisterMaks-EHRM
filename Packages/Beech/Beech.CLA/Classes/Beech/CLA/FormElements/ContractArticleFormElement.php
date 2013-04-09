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
	 * @var array
	 */
	public $articleValues;

	/**
	 * @param string $contractArticle
	 * @return string
	 */
	public function prepareArticleText($contractArticle) {
		$pattern = array();
		$replacement = array();
		if (!is_null($contractArticle->getValues())) {
			foreach ($contractArticle->getValues() as $value) {
				if (isset($value['valueId'])) {
					$identifier = $this->fieldDefaultValueHelper->generateIdentifierForArticle('contractCreator', $contractArticle->getArticleId(), $value['valueId'] . '_text');
					$pattern[] = sprintf('/<(%s)>/', $value['valueId']);
					if (isset($this->articleValues[$value['valueId']]) && is_string($this->articleValues[$value['valueId']])) {
						$replacement[] = sprintf('<strong>%s</strong>', $this->articleValues[$value['valueId']]);;
					} else {
						$replacement[] = sprintf('<strong id="%s">%s</strong>', $identifier, $this->fieldDefaultValueHelper->getDefault($value));
					}
				}
			}
		}
		return preg_replace($pattern, $replacement, $contractArticle->getArticleText());
	}
}

?>