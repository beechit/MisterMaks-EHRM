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
	 * @var string
	 */
	protected $contractTemplate = NULL;

	/**
	 * Array of ContractArticles
	 * @var array
	 */
	protected $contractArticles = array();

	/**
	 * Initialize form element
	 *
	 * @return void
	 */
	public function initializeFormElement() {
		$this->setLabel('Parameters');
		if ($this->contractTemplate !== NULL) {
			foreach ($this->contractArticles as $contractArticle) {
				$contractArticleValues = $contractArticle->getValues();
				if (is_array($contractArticleValues)) {
					foreach ($contractArticleValues as $value) {
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
				$contractArticleElement = $this->createElement('article-' . $contractArticle->getArticleId() . '-identifier', 'Beech.CLA:ContractArticleFormElement');
				$contractArticleElement->setDefaultValue($contractArticle->getArticleId());
				$contractArticleElement->setLabel($contractArticle->getArticleHeader());
				$contractArticleElement->setProperty('contractArticle', $contractArticle);
				$contractArticleElement->setProperty('preparedArticleText', $contractArticleElement->prepareArticleText($contractArticle));
			}
		}
	}

	/**
	 * @param string $contractTemplate
	 */
	public function setContractTemplate($contractTemplate) {
		$this->contractTemplate = $contractTemplate;
	}

	/**
	 * @return string
	 */
	public function getContractTemplate() {
		return $this->contractTemplate;
	}

	/**
	 * Get paginated array of ContractArticles
	 * @param integer $offset
	 * @param integer $length
	 * @return array
	 */
	public function getContractArticles($offset, $length) {
		$this->contractArticles = $this->contractArticleRepository->findByArticles($this->getArticleIds(), $offset, $length);
		return $this->contractArticles;
	}

	/**
	 * @return mixed
	 */
	public function getArticleIds() {
		$parsedYaml = \Symfony\Component\Yaml\Yaml::parse($this->getContractTemplate());
		return \TYPO3\Flow\Utility\Arrays::getValueByPath($parsedYaml, 'contractTemplate.articles');
	}


}

?>