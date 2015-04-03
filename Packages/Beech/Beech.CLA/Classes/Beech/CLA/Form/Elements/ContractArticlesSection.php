<?php
namespace Beech\CLA\Form\Elements;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 01-02-13 09:48
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\TypeHandling;
use Beech\Ehrm\Validation\Validator;

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
	 *
	 * @var array
	 */
	protected $contractArticles = array();

	/**
	 * @var \Beech\CLA\Domain\Model\Contract
	 */
	protected $contract = NULL;

	/**
	 * @var integer
	 */
	protected $articleIndex = 0;

	/**
	 * @var \Beech\Ehrm\Form\Helper\FieldDefaultValueHelper
	 * @Flow\Inject
	 */
	protected $fieldDefaultValueHelper;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings
	 */
	public function injectSettings(array $settings) {
		$this->settings = $settings;
	}

	/**
	 * Article index is dynamic value which depends on page index and
	 * It must be recalculated for each ArticleSection
	 *
	 * @return void
	 */
	public function initArticleIndex() {
		$this->articleIndex = ($this->getParentRenderable()->getIndex()) * $this->settings['contractArticlesPerPage'];
	}

	/**
	 * Initialize form element
	 *
	 * @return void
	 */
	public function initializeFormElement() {
		if ($this->contractTemplate !== NULL) {
			$filledContractValues = array();
			if ($this->contract !== NULL) {
				$filledContractValues = $this->contract->getArticles();
			}
			$this->initArticleIndex();
			foreach ($this->contractArticles as $contractArticle) {

				/** @var $contractArticleSection \TYPO3\Form\Core\Model\FormElementInterface */
				$contractArticleSection = $this->createElement('article-section-' . $contractArticle->getArticleId() . '-identifier', 'Beech.CLA:ContractArticleContainer');
				$contractArticleSection->setLabel($contractArticle->getArticleHeader());
				$contractArticleSection->setProperty('help', $contractArticle->getHelp());
				$contractArticleSection->setProperty('required', $contractArticle->getRequired());
				$contractArticleValues = $contractArticle->getValues();

				$contractArticleValueIdentifier = $this->fieldDefaultValueHelper->generateIdentifierForArticle('', $contractArticle->getArticleId(), 'textOnly');
				$contractArticleValue = $contractArticleSection->createElement($contractArticleValueIdentifier, 'TYPO3.Form:HiddenField');
				$contractArticleValues = $contractArticle->getValues();
					// create one hidden field to force saving textOnly articles too.
				if (is_array($contractArticleValues)) {
						// TODO: check why set default to false is saved in couchDB as empty string
					$contractArticleValue->setDefaultValue(FALSE);

					foreach ($contractArticleValues as $value) {

						$contractArticleValueIdentifier = $this->fieldDefaultValueHelper->generateIdentifierForArticle('', $contractArticle->getArticleId(), $value['valueId']);
						if (isset($value['type']) && preg_match('/(\w+)\.(\w+):(\w+)/', $value['type'])) {
							$contractArticleValue = $contractArticleSection->createElement($contractArticleValueIdentifier, $value['type']);
							if (isset($value['properties'])) {
								foreach ($value['properties'] as $propertyName => $property) {
									$contractArticleValue->setProperty($propertyName, $property);
								}
							}
							if (in_array($value['type'], array('TYPO3.Form:SingleSelectDropdown', 'TYPO3.Form:MultipleSelectCheckboxes', 'Beech.CLA:SingleSelectDropdown', 'Beech.CLA:MultipleSelectCheckboxes', 'Beech.Party:WorkDaySelect'))) {
								if (isset($value['options'])) {
									$contractArticleValue->setProperty('options', $value['options']);
								}
							}

						} else {
							$contractArticleValue = $contractArticleSection->createElement($contractArticleValueIdentifier, 'Beech.CLA:SingleLineText');
						}
						if (isset($value['label'])) {
							$contractArticleValue->setLabel($value['label']);
						} elseif (isset($value['valueId'])) {
							$contractArticleValue->setLabel($value['valueId']);
						}
						if (isset($value['validation'])) {
							foreach ($value['validation'] as $validation) {
								if (!empty($validation['type'])) {
									$validator = $contractArticleValue->createValidator($validation['type'], isset($validation['options']) ? $validation['options'] : array());
									$contractArticleValue->addValidator($validator);
								}
							}
						}
							// check if value exists is contract and use as default value
						$getterName = 'get'.ucfirst($value['valueId']);
						if(!is_null($this->contract->$getterName())) {
							$contractArticleValue->setDefaultValue($this->contract->$getterName());
						} elseif (isset($value['default'])) {
							$contractArticleValue->setDefaultValue($value['default']);
						}
					}
				} else {
					$contractArticleValue->setDefaultValue(TRUE);
				}

				$contractArticleIsSelectedValue =  $this->createElement('article-section-' . $contractArticle->getArticleId() . '-isSelected', 'Beech.CLA:HiddenField');
				$contractArticleIsSelectedValue->setDefaultValue($contractArticle->getRequired() ? 'TRUE' : 'FALSE');
				/** @var $contractArticleElement \Beech\CLA\FormElements\ContractArticleFormElement */
				$contractArticleElement = $contractArticleSection->createElement('article-' . $contractArticle->getArticleId() . '-identifier', 'Beech.CLA:ContractArticleFormElement');
				if (isset($filledContractValues[$contractArticle->getArticleId()])) {
					$contractArticleElement->articleValues = $filledContractValues[$contractArticle->getArticleId()];
				}
				$contractArticleElement->setDefaultValue($contractArticle->getArticleId());
				$contractArticleElement->setContractArticle($contractArticle);
				$contractArticleElement->setProperty('articleIndex', ++$this->articleIndex);
			}
		}
	}

	/**
	 * @param \Beech\CLA\Domain\Model\Contract $contract
	 */
	public function setContract(\Beech\CLA\Domain\Model\Contract $contract) {
		$this->contract = $contract;
		$this->setContractTemplate($contract->getContractTemplate());
	}

	/**
	 * @param \Beech\CLA\Domain\Model\ContractTemplate|string $contractTemplate
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
	 *
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
		$articlesIds = array();
		if ($this->getContractTemplate() !== NULL) {
			$articlesIds = $this->getContractTemplate()->getArticles();
		}
		return $articlesIds;
	}
}

?>
