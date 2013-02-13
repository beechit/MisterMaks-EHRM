<?php
namespace Beech\CLA\FormElements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-02-13 09:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;

/**
 * Contract articles list section
 */
class ContractArticlesSection extends \TYPO3\Form\FormElements\Section {

	/**
	 * @var \Beech\CLA\Domain\Repository\ContractArticleRepository
	 * @Flow\Inject
	 */
	protected $contractArticleRepository;

	/**
	 * Initialize form element
	 */
	public function initializeFormElement() {

		$this->setLabel('Parameters');
			//TODO: filter by contractTemplate, results from previous page
		$contractArticles = $this->contractArticleRepository->findAll();
		foreach ($contractArticles as $key => $contractArticle) {
			if (!is_null($contractArticle->getValues()) ) {
				foreach ($contractArticle->getValues() as $k => $value) {
					$contractArticleValueIdentifier = 'article-' . $contractArticle->getArticleId() . '-values.' . $value['valueId'];
					if (isset($value['type']) && preg_match('/(\w+)\.(\w+):(\w+)/', $value['type'])) {
						$contractArticleValue = $this->createElement($contractArticleValueIdentifier, $value['type']);
						if (isset($value['properties'])) {
							foreach ($value['properties'] as $propertyName => $property) {
								$contractArticleValue->setProperty($propertyName, $property);
							}
						}
						if ($value['type'] === 'TYPO3.Form:SingleSelectDropdown' || $value['type'] === 'TYPO3.Form:MultipleSelectCheckboxes') {
							if (isset($value['options'])) {
								$contractArticleValue->setProperty('options', $value['options']);
							}
						}
						if (isset($value['default'])) {
							$contractArticleValue->setDefaultValue($value['default']);
						}
					} else {
						$contractArticleValue = $this->createElement($contractArticleValueIdentifier, 'TYPO3.Form:SingleLineText');
					}
					if (isset($value['valueId'])) {
						$contractArticleValue->setLabel($value['valueId']);
					}
				}
			}
			$contractArticleElement = $this->createElement('article-' . $contractArticle->getArticleId() . '-identifier' , 'Beech.CLA:ContractArticleFormElement');
			$contractArticleElement->setDefaultValue($contractArticle->getArticleId());
			$contractArticleElement->setLabel($contractArticle->getArticleHeader());
			$contractArticleElement->setProperty('contractArticle', $contractArticle);
			$contractArticleElement->setProperty('preparedArticleText', $contractArticleElement->prepareArticleText($contractArticle));
		}
	}

	/**
	 * Before rendering we need data from previous page
	 *
	 * @param \TYPO3\Form\Core\Runtime\FormRuntime $formRuntime
	 * @return void
	 */
	public function beforeRendering(\TYPO3\Form\Core\Runtime\FormRuntime $formRuntime) {
		//TODO: filtering
	}
}
?>
