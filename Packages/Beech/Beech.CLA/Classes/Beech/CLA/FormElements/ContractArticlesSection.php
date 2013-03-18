<?php
namespace Beech\CLA\FormElements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-02-13 09:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\TypeHandling;

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
	 * @var \Beech\CLA\Domain\Repository\ContractTemplateRepository
	 * @Flow\Inject
	 */
	protected $contractTemplateRepository;

	/**
	 * @var \Beech\CLA\Domain\Model\ContractTemplate
	 */
	protected $contractTemplate = NULL;

	/**
	 * Array of ContractArticles
	 * @var array
	 */
	protected $contractArticles = array();

	/**
	 * @var \Beech\CLA\Domain\Model\Contract
	 */
	protected $contract = NULL;

	/**
	 * @var \Beech\Ehrm\Form\Helper\FieldDefaultValueHelper
	 * @Flow\Inject
	 */
	protected $fieldDefaultValueHelper;

	/**
	 * Initialize form element
	 *
	 * @return void
	 */
	public function initializeFormElement() {
		$this->setLabel('Parameters');

		if ($this->contractTemplate !== NULL) {
			if ($this->contract !== NULL) {
				$filledContractValues = $this->contract->getArticles();
			}
			foreach ($this->contractArticles as $contractArticle) {
				$contractArticleSection = $this->createElement('article-section-' . $contractArticle->getArticleId() . '-identifier', 'Beech.CLA:ContractArticleContainer');
				$contractArticleSection->setLabel($contractArticle->getArticleHeader());
				$contractArticleSection->setProperty('help', $contractArticle->getHelp());
				$contractArticleValues = $contractArticle->getValues();

				if (is_array($contractArticleValues)) {

					foreach ($contractArticleValues as $value) {

						$contractArticleValueIdentifier = $this->fieldDefaultValueHelper->generateIdentifierForArticle('', $contractArticle->getArticleId(), $value['valueId']);
						if (isset($value['type']) && preg_match('/(\w+)\.(\w+):(\w+)/', $value['type'])) {
							$contractArticleValue = $contractArticleSection->createElement($contractArticleValueIdentifier, $value['type']);
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

						} else {
							$contractArticleValue = $contractArticleSection->createElement($contractArticleValueIdentifier, 'TYPO3.Form:SingleLineText');
						}
						if (isset($value['label'])) {
							$contractArticleValue->setLabel($value['label']);
						} else if (isset($value['valueId'])) {
							$contractArticleValue->setLabel($value['valueId']);
						}
						if (isset($value['validation'])) {
							$validator = $contractArticleValue->createValidator($value['validation']['type'], $value['validation']['options']);
							$contractArticleValue->addValidator($validator);
						}

						if (isset($value['default'])) {
							$contractArticleValue->setDefaultValue($value['default']);
						}
					}
				}
				$contractArticleElement = $contractArticleSection->createElement('article-' . $contractArticle->getArticleId() . '-identifier', 'Beech.CLA:ContractArticleFormElement');
				if (!empty($filledContractValues) && isset($filledContractValues[$contractArticle->getArticleId()])) {
					$contractArticleElement->articleValues = $filledContractValues[$contractArticle->getArticleId()];
				}
				$contractArticleElement->setDefaultValue($contractArticle->getArticleId());
				$contractArticleElement->setProperty('contractArticle', $contractArticle);
				$contractArticleElement->setProperty('preparedArticleText', $contractArticleElement->prepareArticleText($contractArticle));
			}
		}
	}

	/**
	 * @param mixed $contractTemplate
	 */
	public function setContract($contract) {
		$this->contract = $contract;
		$this->setContractTemplate($contract->getContractTemplate());
	}

	/**
	 * @param mixed $contractTemplate
	 */
	public function setContractTemplate($contractTemplate) {
		if (is_string($contractTemplate)) {
			$contractTemplate = $this->contractTemplateRepository->findByIdentifier($contractTemplate);
		}
		$this->contractTemplate = $contractTemplate;
	}

	/**
	 * @return string
	 */
	public function getContractTemplate() {
		return $this->contractTemplate;
	}

	/**
	 * @param integer $offset
	 * @param integer $length
	 */
	public function initContractArticles($offset = 0, $length = NULL) {
		$this->contractArticles = $this->contractArticleRepository->findByArticles($this->getArticleIds(), $offset, $length);
	}

	/**
	 * Get paginated array of ContractArticles
	 * @param integer $offset
	 * @param integer $length
	 * @return array
	 */
	public function getContractArticles($offset = 0, $length = NULL) {
		if (empty($this->contractArticles)) {
			$this->initContractArticles($offset, $length);
		}
		return $this->contractArticles;
	}

	/**
	 * @return mixed
	 */
	public function getArticleIds() {
		return  $this->getContractTemplate()->getArticles();
	}


}

?>