<?php
namespace Beech\CLA\Domain\Model;

/*
 * This source file is proprietary property of Beech Applications B.V.
 * Date: 18-03-13 16:10
 * All code (c) Beech Applications B.V. all rights reserved
 */

use TYPO3\Flow\Annotations as Flow,
	Doctrine\ODM\CouchDB\Mapping\Annotations as ODM;

/**
 * ContractTemplate, predefined rules for contract
 *
 * @ODM\Document(indexed="true")
 */
class ContractTemplate extends \Beech\Ehrm\Domain\Model\Document {

	/**
	 * The templateName
	 *
	 * @var string
	 * @ODM\Field(type="string")
	 */
	protected $templateName;

	/**
	 * Articles
	 *
	 * @var array
	 * @ODM\Field(type="mixed")
	 */
	protected $articles = [];

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\I18n\Service
	 */
	protected $localizationService;

	/**
	 * Set templateName
	 *
	 * @param string $templateName
	 */
	public function setTemplateName($templateName) {
		$this->templateName = $templateName;
	}

	/**
	 * Get templateName
	 *
	 * @return string
	 */
	public function getTemplateName() {
		return $this->templateName;
	}

	/**
	 * Set articles
	 *
	 * @param array $articles
	 */
	public function setArticles($articles) {
		$this->articles = $articles;
	}

	/**
	 * Get articles
	 *
	 * @return array
	 */
	public function getArticles() {
		return $this->articles;
	}

	/**
	 * Get contract title
	 * Override parent method to display contractTitle in correct translation
	 *
	 * @return string
	 */
	public function getContractTitle() {
		$contractTitle = parent::getContractTitle();
		return $contractTitle[$this->getCurrentLanguage()];
	}

	/**
	 * Get current language based on current locale
	 *
	 * @return string
	 */
	private function getCurrentLanguage() {
		$language = substr($this->localizationService->getConfiguration()->getCurrentLocale(), 0, 2);
		// TODO: get default language from settings
		return ($language != '') ? $language : 'nl';
	}
}

?>