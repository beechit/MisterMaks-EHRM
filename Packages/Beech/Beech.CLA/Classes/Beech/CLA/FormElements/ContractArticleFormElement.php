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

	public function prepareArticleText($contractArticle) {
		$pattern = array();
		$replacement = array();
		if (!is_null($contractArticle->getValues())) {
			foreach ($contractArticle->getValues() as $k => $value) {
				if (isset($value['valueId'])) {
					$pattern[] = sprintf('/<(%s)>/', $value['valueId']);
					$replacement[] = sprintf('<span id="$1"><b>%s</b></span>', isset($value['default']) ? $value['default'] : 0);
				}
			}
		}
		return preg_replace($pattern, $replacement, $contractArticle->getArticleText()) ;
	}
}
?>